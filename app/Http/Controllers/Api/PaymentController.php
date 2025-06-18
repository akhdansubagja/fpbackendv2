<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProofRequest;
use App\Models\Payment;
use App\Models\User; // <-- Tambahkan ini
use App\Models\Notification; // <-- Tambahkan ini
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
        if ($request->user()->id !== $payment->rental->user_id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $file = $request->file('bukti_pembayaran');

        if ($payment->bukti_pembayaran) {
            Storage::delete(str_replace(Storage::url(''), 'public', $payment->bukti_pembayaran));
        }

        $path = $file->store('public/payments/proofs');

        $payment->update([
            'bukti_pembayaran' => Storage::url($path)
        ]);

        // === BAGIAN BARU: BUAT NOTIFIKASI UNTUK ADMIN ===
        $admins = User::where('role', 'admin')->get();
        $penyewa = $payment->rental->user; // Mengambil data penyewa dari relasi

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Bukti Pembayaran Diunggah',
                'message' => "Bukti bayar untuk sewa #{$payment->rental_id} oleh {$penyewa->name} telah diunggah."
            ]);
        }
        // === AKHIR BAGIAN BARU ===

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diunggah.',
            'data' => $payment
        ]);
    }

}