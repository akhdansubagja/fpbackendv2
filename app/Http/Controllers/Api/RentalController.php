<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentalRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RentalController extends Controller
{
    // PERUBAHAN: Menggunakan biaya antar tetap (flat fee)
    private const FLAT_DELIVERY_FEE = 150000;

    /**
     * Menyimpan data pemesanan baru dari pengguna.
     */
    public function store(StoreRentalRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = $request->user();

        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

        $waktuSewa = Carbon::parse($validatedData['waktu_sewa']);
        $waktuKembali = Carbon::parse($validatedData['waktu_kembali']);
        $durasiHari = $waktuSewa->diffInDays($waktuKembali);
        $durasiHari = $durasiHari < 1 ? 1 : $durasiHari;

        // --- LOGIKA BARU: HITUNG DELIVERY FEE (FLAT) ---
        $deliveryFee = 0;
        if (isset($validatedData['delivery_option']) && $validatedData['delivery_option'] === 'delivered') {
            // Biaya antar sekarang tidak dikalikan dengan hari
            $deliveryFee = self::FLAT_DELIVERY_FEE;
        }
        // --- AKHIR LOGIKA BARU ---

        $hargaSewaMobil = $durasiHari * $vehicle->harga_sewa_harian;
        $totalHarga = $hargaSewaMobil + $deliveryFee;
        
        $validatedData['user_id'] = $user->id;
        $validatedData['total_harga'] = $totalHarga;
        $validatedData['delivery_fee'] = $deliveryFee;
        $validatedData['status_pemesanan'] = 'pending';

        $rental = $user->rentals()->create($validatedData);

        $rental->payment()->create([
            'jumlah_bayar' => $totalHarga,
            'security_deposit' => $vehicle->security_deposit ?? 0, // Menggunakan null coalescing operator
            'status_pembayaran' => 'pending',
            'status_deposit' => 'ditahan',
            'metode_pembayaran' => 'transfer'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan Anda berhasil dibuat dan sedang menunggu konfirmasi.',
            'data' => $rental->load('payment')
        ], 201);
    }


    /**
     * Menampilkan riwayat pemesanan milik pengguna yang sedang login.
     */
    public function history(Request $request): JsonResponse
    {
        // Ambil user yang sedang login berdasarkan tokennya
        $user = $request->user();

        // Ambil semua data rental milik user tersebut,
        // beserta data relasi vehicle dan payment-nya.
        $rentals = $user->rentals()->with(['vehicle', 'payment'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pemesanan berhasil diambil.',
            'data' => $rentals
        ]);
    }
}