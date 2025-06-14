<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi images untuk ditampilkan di admin
        $vehicles = Vehicle::with('images')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua kendaraan berhasil diambil.',
            'data' => $vehicles
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Mengelola upload file foto utama
        if ($request->hasFile('foto_utama')) {
            $path = $request->file('foto_utama')->store('public/vehicles/main');
            $validatedData['foto_utama'] = Storage::url($path);
        }

        // Membuat data kendaraan baru
        $vehicle = Vehicle::create($validatedData);

        // Mengelola upload file galeri foto
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $imageFile) {
                $path = $imageFile->store('public/vehicles/gallery');
                // Buat entri baru di tabel vehicle_images
                $vehicle->images()->create(['path' => Storage::url($path)]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kendaraan baru berhasil ditambahkan.',
            'data' => $vehicle->load('images') // Muat relasi images untuk respons
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Muat juga relasi images saat menampilkan detail
        $vehicle = Vehicle::with('images')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail data kendaraan berhasil diambil.',
            'data' => $vehicle
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $validatedData = $request->validated();

        if ($request->hasFile('foto_utama')) {
            if ($vehicle->foto_utama) {
                Storage::delete(str_replace('/storage', 'public', $vehicle->foto_utama));
            }
            $path = $request->file('foto_utama')->store('public/vehicles/main');
            $validatedData['foto_utama'] = Storage::url($path);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $imageFile) {
                $path = $imageFile->store('public/vehicles/gallery');
                $vehicle->images()->create(['path' => Storage::url($path)]);
            }
        }
        
        $vehicle->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data kendaraan berhasil diperbarui.',
            'data' => $vehicle->load('images')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::with('images')->findOrFail($id);

        // Hapus foto utama
        if ($vehicle->foto_utama) {
            Storage::delete(str_replace('/storage', 'public', $vehicle->foto_utama));
        }

        // Hapus semua foto di galeri
        foreach ($vehicle->images as $image) {
            Storage::delete(str_replace('/storage', 'public', $image->path));
        }
        
        // Hapus data dari database (record di vehicle_images akan terhapus otomatis karena cascade)
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kendaraan berhasil dihapus.'
        ], 200);
    }
}