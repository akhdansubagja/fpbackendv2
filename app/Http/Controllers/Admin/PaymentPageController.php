<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Pastikan use statement ini ada

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

        } elseif ($validated['status_pembayaran'] == 'gagal') {
            // JIKA GAGAL, MAKA BATALKAN PESANAN
            $payment->rental()->update(['status_pemesanan' => 'dibatalkan']);
        }
        // Jika statusnya 'pending', kita tidak melakukan apa-apa pada status pemesanan.

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