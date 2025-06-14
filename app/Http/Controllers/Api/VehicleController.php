<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    /**
     * Menampilkan daftar kendaraan yang statusnya 'tersedia'.
     */
    public function index(): JsonResponse
    {
        // Tambahkan with('images') untuk mengambil data galeri foto
        $vehicles = Vehicle::with('images')->where('status', 'tersedia')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kendaraan tersedia berhasil diambil.',
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
            $vehicle = Vehicle::with('images')->where('status', 'tersedia')->findOrFail($id);

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
}