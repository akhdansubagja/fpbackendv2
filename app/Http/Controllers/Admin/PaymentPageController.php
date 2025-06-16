<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

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
        $request->validate([
            'status_pembayaran' => 'required|in:lunas,gagal',
        ]);

        $payment->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}