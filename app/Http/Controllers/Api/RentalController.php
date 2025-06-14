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
    /**
     * Menyimpan data pemesanan baru dari pengguna.
     */
    public function store(StoreRentalRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = $request->user();

        // Ambil data kendaraan untuk menghitung harga
        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

        // Kalkulasi durasi sewa dalam hari
        $waktuSewa = Carbon::parse($validatedData['waktu_sewa']);
        $waktuKembali = Carbon::parse($validatedData['waktu_kembali']);
        $durasiHari = $waktuSewa->diffInDays($waktuKembali);
        
        // Pastikan durasi minimal 1 hari
        if ($durasiHari < 1) {
            $durasiHari = 1;
        }

        // Kalkulasi total harga
        $totalHarga = $durasiHari * $vehicle->harga_sewa_harian;
        
        // Tambahkan user_id dan total_harga ke data yang akan disimpan
        $validatedData['user_id'] = $user->id;
        $validatedData['total_harga'] = $totalHarga;
        $validatedData['status_pemesanan'] = 'pending'; // Status awal

        // Simpan data rental
        $rental = $user->rentals()->create($validatedData);

        // Buat data payment yang terhubung dengan rental ini
        $rental->payment()->create([
            'jumlah_bayar' => $totalHarga,
            'security_deposit' => 200000, // Contoh security deposit
            'status_pembayaran' => 'pending',
            'status_deposit' => 'ditahan',
            'metode_pembayaran' => 'transfer' // Default, bisa diubah nanti
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan Anda berhasil dibuat dan sedang menunggu konfirmasi.',
            'data' => $rental->load('payment') // Muat data payment dalam respons
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