<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VehicleController extends Controller
{
    /**
     * Menampilkan daftar kendaraan yang statusnya 'tersedia'.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::with('images'); // Mulai query

        // Jika ada parameter pickup_date dari frontend
        if ($request->has('pickup_date')) {
            $pickupDate = Carbon::parse($request->input('pickup_date'))->format('Y-m-d');

            // Ambil semua ID mobil yang TIDAK TERSEDIA pada tanggal tersebut
            $unavailableVehicleIds = Rental::where(function ($query) use ($pickupDate) {
                $query->whereDate('waktu_sewa', '<=', $pickupDate)
                    ->whereDate('waktu_kembali', '>=', $pickupDate);
            })
                ->where('status_pemesanan', '!=', 'dibatalkan')
                ->pluck('vehicle_id');

            // Ambil mobil yang ID-nya TIDAK ADA di dalam daftar yang tidak tersedia
            $query->whereNotIn('id', $unavailableVehicleIds);
        }

        $vehicles = $query->latest()->get(); // Eksekusi query

        return response()->json([
            'success' => true,
            'message' => 'Daftar kendaraan berhasil diambil.',
            'data' => $vehicles
        ]);
    }

    /**
     * Menampilkan detail satu kendaraan spesifik.
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Tambahkan with('images') di sini juga
            $vehicle = Vehicle::with('images')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail data kendaraan berhasil diambil.',
                'data' => $vehicle
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data kendaraan tidak ditemukan atau tidak tersedia.'
            ], 404);
        }
    }

    /**
     * Mengambil semua tanggal yang sudah dipesan untuk sebuah kendaraan,
     * termasuk 2 hari masa tenggang setelahnya.
     */
    public function getBookedDates(Vehicle $vehicle): JsonResponse
    {
        $bookedDates = [];
        $gracePeriodDays = 2; // Masa tenggang 2 hari untuk pembersihan/pengecekan

        // Ambil semua rental yang relevan (bukan yang dibatalkan atau selesai lama)
        $rentals = $vehicle->rentals()
            ->where('status_pemesanan', '!=', 'dibatalkan')
            ->where('waktu_kembali', '>=', Carbon::now()->subMonths(1)) // Ambil data 1 bulan ke belakang
            ->get();

        foreach ($rentals as $rental) {
            $startDate = Carbon::parse($rental->waktu_sewa);
            // Tambahkan masa tenggang pada tanggal kembali
            $endDate = Carbon::parse($rental->waktu_kembali)->addDays($gracePeriodDays);

            // Buat rentang tanggal dari awal sewa sampai akhir masa tenggang
            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                // Tambahkan setiap tanggal ke dalam array dalam format Y-m-d
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        // Hapus duplikat tanggal dan kembalikan sebagai JSON
        return response()->json([
            'success' => true,
            'data' => array_values(array_unique($bookedDates))
        ]);
    }
}