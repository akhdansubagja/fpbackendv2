<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Pastikan use statement ini ada
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail; // Ini akan kita buat di langkah selanjutnya
use Illuminate\Support\Facades\Log; // Untuk mencatat error jika ada
use Illuminate\Models\User; // Untuk mengambil data user terkait

class PaymentPageController extends Controller
{
    /**
     * Menampilkan halaman daftar pembayaran.
     */
    public function index()
    {
        // Eager load relasi rental, dan di dalam rental, load relasi user
        $payments = Payment::with(['rental.user'])->latest()->get();

        return view('admin.payments.index', ['payments' => $payments]);
    }

    /**
     * Memperbarui status pembayaran.
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        // Validasi untuk status pembayaran
        $validated = $request->validate([
            'status_pembayaran' => ['required', Rule::in(['pending', 'lunas', 'gagal'])]
        ]);

        // Update status pembayaran
        $payment->update($validated);

        // --- LOGIKA REAKSI BERANTAI YANG DIPERBARUI ---
        if ($validated['status_pembayaran'] == 'lunas') {
            // Jika lunas, konfirmasi pesanan
            $payment->rental()->update(['status_pemesanan' => 'dikonfirmasi']);

            // Ambil data pengguna yang terkait
            $user = $payment->rental->user;

            // 1. Kirim PUSH NOTIFICATION (jika user punya token)
            if ($user && $user->fcm_token) {
                try {
                    $messaging = app('firebase.messaging');
                    $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $user->fcm_token)
                        ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
                            'Pembayaran Diterima!',
                            'Pembayaran untuk pesanan #' . $payment->rental_id . ' telah kami konfirmasi.'
                        ));
                    $messaging->send($message);
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim FCM Notifikasi: ' . $e->getMessage());
                }
            }

            // 2. Kirim EMAIL KONFIRMASI
            try {
                Mail::to($user->email)->send(new PaymentSuccessMail($payment->rental));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim Email Konfirmasi Pembayaran: ' . $e->getMessage());
            }

        } elseif ($validated['status_pembayaran'] == 'gagal') {
            // Jika gagal, batalkan pesanan
            $payment->rental()->update(['status_pemesanan' => 'dibatalkan']);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    /**
     * Memperbarui status dan jumlah pengembalian deposit.
     */
    public function updateDeposit(Request $request, Payment $payment)
    {
        // Ambil total security deposit dari kendaraan terkait
        $totalDeposit = $payment->rental->vehicle->security_deposit;

        // Validasi input dari admin
        $validated = $request->validate([
            'deposit_dikembalikan' => "required|numeric|min:0|max:{$totalDeposit}"
        ]);

        $refundAmount = (float) $validated['deposit_dikembalikan'];
        $deductedAmount = $totalDeposit - $refundAmount;
        $newStatus = 'ditahan'; // Default status

        if ($refundAmount == $totalDeposit) {
            $newStatus = 'dikembalikan';
        } elseif ($refundAmount > 0 && $refundAmount < $totalDeposit) {
            $newStatus = 'dipotong';
        }
        // Jika refundAmount adalah 0, status tetap 'ditahan'

        // Update data di database
        $payment->update([
            'deposit_dikembalikan' => $refundAmount,
            'deposit_dipotong' => $deductedAmount,
            'status_deposit' => $newStatus,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Status deposit berhasil diperbarui!');
    }
}