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
        // === KALKULASI KEUANGAN BARU ===
        $totalUangMasuk = Payment::where('status_pembayaran', 'lunas')->sum('jumlah_bayar');
        $totalPengembalianDeposit = Payment::sum('deposit_dikembalikan');
        $pendapatanBersih = $totalUangMasuk - $totalPengembalianDeposit;

        $financialStats = [
            'total_uang_masuk' => $totalUangMasuk,
            'pendapatan_bersih' => $pendapatanBersih,
            'total_pengembalian_deposit' => $totalPengembalianDeposit
        ];

        // === STATISTIK OPERASIONAL ===
        $summaryData = [
            'transaksi_pending' => Rental::where('status_pemesanan', 'pending')->count(),
            'transaksi_berjalan' => Rental::whereIn('status_pemesanan', ['dikonfirmasi', 'berjalan'])->count(),
            'jumlah_kendaraan' => Vehicle::count(),
            'jumlah_pengguna' => User::where('role', 'penyewa')->count(),
        ];

        // === DATA GRAFIK PENDAPATAN ===
        $monthlyRevenue = Payment::select(
            DB::raw('YEAR(tanggal_pembayaran) as year, MONTH(tanggal_pembayaran) as month'),
            DB::raw('SUM(jumlah_bayar - deposit_dikembalikan) as total_bersih')
        )
            ->where('status_pembayaran', 'lunas')
            ->where('tanggal_pembayaran', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $chartLabels = $monthlyRevenue->map(fn($item) => Carbon::createFromDate($item->year, $item->month)->format('F Y'));
        $chartData = $monthlyRevenue->pluck('total_bersih');

        // === DATA KENDARAAN TERLARIS ===
        $topVehicles = DB::table('rentals')
            ->join('vehicles', 'rentals.vehicle_id', '=', 'vehicles.id')
            ->select('vehicles.merk', 'vehicles.nama', DB::raw('count(rentals.id) as rental_count'))
            ->groupBy('vehicles.id', 'vehicles.merk', 'vehicles.nama')
            ->orderByDesc('rental_count')->limit(5)->get();

        // ===== LOGIKA YANG HILANG (DITAMBAHKAN KEMBALI) =====
        $rentalFrequency = Rental::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $frequencyLabels = $rentalFrequency->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M'));
        $frequencyData = $rentalFrequency->pluck('count');
        // ========================================================

        // Kirim semua data yang dibutuhkan ke view
        return view('admin.dashboard', compact(
            'financialStats',
            'summaryData',
            'chartLabels',
            'chartData',
            'topVehicles',
            'frequencyLabels', // <-- Pastikan ini ada
            'frequencyData'   // <-- Pastikan ini ada
        ));
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