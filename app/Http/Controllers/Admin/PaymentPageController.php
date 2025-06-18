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
        
        // --- LOGIKA REAKSI BERANTAI (Sesuai keputusan terakhir kita) ---
        // Jika pembayaran lunas, maka konfirmasi pesanan dan set mobil menjadi disewa
        if ($validated['status_pembayaran'] == 'lunas') {
            $payment->rental()->update(['status_pemesanan' => 'dikonfirmasi']);
            $payment->rental->vehicle()->update(['status' => 'disewa']);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}