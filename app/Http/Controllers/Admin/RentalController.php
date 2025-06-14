<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class RentalController extends Controller
{
    /**
     * Menampilkan daftar semua data pemesanan (rental).
     */
    public function index(): JsonResponse
    {
        // Ambil semua data rental, beserta data relasi user dan vehicle-nya
        // untuk ditampilkan di frontend nanti.
        // PERBAIKAN
        $rentals = Rental::with(['user', 'vehicle', 'payment'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data pemesanan berhasil diambil.',
            'data' => $rentals,
        ]);
    }

    /**
     * Memperbarui status sebuah pemesanan.
     */
    public function updateStatus(Request $request, Rental $rental): JsonResponse
    {
        // Validasi input
        $request->validate([
            'status_pemesanan' => ['required', Rule::in(['dikonfirmasi', 'ditolak', 'selesai'])]
        ]);

        // Update status pemesanan
        $rental->update([
            'status_pemesanan' => $request->status_pemesanan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pemesanan berhasil diperbarui.',
            'data' => $rental,
        ]);
    }
}