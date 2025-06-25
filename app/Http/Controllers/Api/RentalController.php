<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentalRequest;
use App\Models\Vehicle;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    private const FLAT_DELIVERY_FEE = 150000;

    /**
     * Menyimpan data pemesanan baru dari pengguna.
     */
    public function store(StoreRentalRequest $request): JsonResponse
    {
        // Ambil data yang sudah lolos validasi dari StoreRentalRequest
        $validatedData = $request->validated();
        $user = $request->user();

        DB::beginTransaction();

        try {
            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

            $waktuSewa = Carbon::parse($validatedData['waktu_sewa']);
            $waktuKembali = Carbon::parse($validatedData['waktu_kembali']);
            $durasiHari = $waktuSewa->diffInDays($waktuKembali) ?: 1;

            $deliveryFee = ($validatedData['delivery_option'] === 'delivered') ? self::FLAT_DELIVERY_FEE : 0;
            $hargaSewaMobil = $durasiHari * $vehicle->harga_sewa_harian;

            // Perhitungan total harga HARUS menyertakan security deposit
            $totalHarga = $hargaSewaMobil + $deliveryFee + $vehicle->security_deposit;

            // 1. Membuat data Rental
            $rental = $user->rentals()->create([
                'vehicle_id' => $vehicle->id,
                'waktu_sewa' => $waktuSewa,
                'waktu_kembali' => $waktuKembali,
                'total_harga' => $totalHarga,
                'status_pemesanan' => 'pending',
                'delivery_option' => $validatedData['delivery_option'],
                'delivery_address' => $validatedData['delivery_address'] ?? null,
                'delivery_fee' => $deliveryFee,
            ]);

            // 2. Meng-handle Upload Bukti Pembayaran
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment/proofs', 'public');
            }

            // 3. Membuat data Payment
            $rental->payment()->create([
                'metode_pembayaran' => $request->input('payment_method'),
                'jumlah_bayar' => $totalHarga,
                'status_pembayaran' => 'pending',

                // --- PERBAIKAN 1: Tambahkan tanggal pembayaran ---
                'tanggal_pembayaran' => Carbon::now(),

                // --- PERBAIKAN 2: Simpan path dengan awalan /storage/ ---
                'bukti_pembayaran' => '/storage/' . $proofPath,

                'status_deposit' => 'ditahan',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan Anda telah diterima dan sedang diproses.',
                'data' => $rental->load('payment')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat pesanan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage() // Tampilkan pesan error untuk debug
            ], 500);
        }
    }

    /**
     * Menampilkan riwayat pemesanan milik pengguna yang sedang login.
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();
        $rentals = $user->rentals()->with(['vehicle', 'payment'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pemesanan berhasil diambil.',
            'data' => $rentals
        ]);
    }

    /**
     * Memeriksa status pembayaran untuk sebuah rental.
     */
    public function checkStatus(Request $request, Rental $rental): JsonResponse
    {
        // 1. Otorisasi: Pastikan hanya pemilik yang bisa cek status
        if ($request->user()->id !== $rental->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 2. Load relasi 'payment' dengan aman
        // Ini memastikan kita tidak akan error bahkan jika relasi payment tidak ada
        $rental->load('payment');

        // 3. Tentukan status pembayaran dengan aman
        $statusPembayaran = 'pending'; // Nilai default jika tidak ada data pembayaran
        if ($rental->payment) {
            $statusPembayaran = $rental->payment->status_pembayaran;
        }

        // 4. Kembalikan respons JSON
        return response()->json([
            'status_pemesanan' => $rental->status_pemesanan,
            'status_pembayaran' => $statusPembayaran,
        ]);
    }
}