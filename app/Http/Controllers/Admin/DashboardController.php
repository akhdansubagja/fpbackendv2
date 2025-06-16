<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini
use Carbon\Carbon; // <-- Tambahkan ini


class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk web.
     */
    public function index()
    {
        // --- Data Statistik Kartu ---
        $summaryData = [
            'total_pendapatan' => Payment::where('status_pembayaran', 'lunas')->sum('jumlah_bayar'),
            'transaksi_pending' => Rental::where('status_pemesanan', 'pending')->count(),
            'transaksi_berjalan' => Rental::whereIn('status_pemesanan', ['dikonfirmasi', 'berjalan'])->count(),
            'jumlah_kendaraan' => Vehicle::count(),
            'jumlah_pengguna' => User::where('role', 'penyewa')->count(),
        ];

        // --- Data untuk Grafik Pendapatan 6 Bulan Terakhir ---
        $monthlyRevenue = Payment::select(
            DB::raw('YEAR(tanggal_pembayaran) as year, MONTH(tanggal_pembayaran) as month'),
            DB::raw('SUM(jumlah_bayar) as total')
        )
            ->where('status_pembayaran', 'lunas')
            ->where('tanggal_pembayaran', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartLabels = $monthlyRevenue->map(function ($item) {
            return Carbon::createFromDate($item->year, $item->month)->format('F Y');
        });

        $chartData = $monthlyRevenue->pluck('total');

        // Hentikan eksekusi dan tampilkan isi variabel di sini
        // dd($chartLabels, $chartData);

        // Baris di bawah ini tidak akan dieksekusi untuk sementara
        return view('admin.dashboard', [
            'stats' => $summaryData,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]);
    }

    /**
     * Menyediakan data ringkasan untuk API.
     */
    public function summary(): JsonResponse // <-- METHOD LAMA UNTUK API TETAP ADA
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