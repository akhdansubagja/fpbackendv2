<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Memperbarui status sebuah pembayaran.
     */
    public function updateStatus(Request $request, Payment $payment): JsonResponse
    {
        $request->validate([
            'status_pembayaran' => ['required', Rule::in(['lunas', 'gagal'])]
        ]);

        $payment->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui.',
            'data' => $payment
        ]);
    }
}