<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Menyediakan data ringkasan untuk dashboard admin.
     */
    public function summary(): JsonResponse
    {
        // Menghitung total pendapatan dari pembayaran yang sudah lunas
        $totalRevenue = Payment::where('status_pembayaran', 'lunas')->sum('jumlah_bayar');

        // Menghitung jumlah transaksi yang masih pending
        $pendingRentals = Rental::where('status_pemesanan', 'pending')->count();

        // Menghitung jumlah transaksi yang sedang berjalan/aktif
        $ongoingRentals = Rental::whereIn('status_pemesanan', ['dikonfirmasi', 'berjalan'])->count();
        
        // Menghitung jumlah total kendaraan
        $totalVehicles = Vehicle::count();

        // Menghitung jumlah total pengguna dengan role 'penyewa'
        $totalUsers = User::where('role', 'penyewa')->count();

        // Menggabungkan semua data ke dalam satu array
        $summaryData = [
            'total_pendapatan' => (float) $totalRevenue,
            'transaksi_pending' => $pendingRentals,
            'transaksi_berjalan' => $ongoingRentals,
            'jumlah_kendaraan' => $totalVehicles,
            'jumlah_pengguna' => $totalUsers,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data ringkasan dashboard berhasil diambil.',
            'data' => $summaryData
        ]);
    }
}