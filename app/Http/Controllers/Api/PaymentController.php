<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProofRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Mengunggah bukti pembayaran untuk sebuah pesanan.
     */
    public function uploadProof(UploadProofRequest $request, Payment $payment): JsonResponse
    {
        // PENTING: Pengecekan Otorisasi
        // Pastikan pengguna yang login adalah pemilik dari rental yang terkait dengan pembayaran ini.
        if ($request->user()->id !== $payment->rental->user_id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        // Ambil file yang sudah divalidasi
        $file = $request->file('bukti_pembayaran');

        // Hapus bukti pembayaran lama jika ada
        if ($payment->bukti_pembayaran) {
            Storage::delete(str_replace('/storage', 'public', $payment->bukti_pembayaran));
        }

        // Simpan file baru dan dapatkan path-nya
        $path = $file->store('public/payments/proofs');
        
        // Update kolom di database dengan path ke file baru
        $payment->update([
            'bukti_pembayaran' => Storage::url($path)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diunggah.',
            'data' => $payment
        ]);
    }
}