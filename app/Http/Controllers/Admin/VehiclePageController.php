<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateVehicleRequest;

class VehiclePageController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('images')->latest()->get();
        return view('admin.vehicles.index', ['vehicles' => $vehicles]);
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi data, termasuk gallery_images
        $validatedData = $request->validate([
            'merk' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'transmisi' => 'required|in:manual,matic',
            'jumlah_kursi' => 'required|integer|min:1',
            'bahan_bakar' => 'required|in:bensin,diesel,listrik,hybrid',
            'has_ac' => 'required|boolean',
            'harga_sewa_harian' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images' => 'nullable|array', // Validasi untuk galeri
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Validasi setiap file
        ]);
        
        $validatedData['status'] = 'tersedia';

        if ($request->hasFile('foto_utama')) {
            $path = $request->file('foto_utama')->store('public/vehicles/main');
            $validatedData['foto_utama'] = Storage::url($path);
        }

        // 2. Simpan data kendaraan utama terlebih dahulu
        $vehicle = Vehicle::create($validatedData);

        // 3. Logika baru untuk menyimpan foto galeri
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $imageFile) {
                $path = $imageFile->store('public/vehicles/gallery');
                // Buat entri baru di tabel vehicle_images yang terhubung dengan kendaraan ini
                $vehicle->images()->create(['path' => Storage::url($path)]);
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan baru berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $vehicle = Vehicle::with('images')->findOrFail($id);
        return view('admin.vehicles.edit', ['vehicle' => $vehicle]);
    }

    /**
     * Memperbarui data kendaraan di database.
     */
    public function update(UpdateVehicleRequest $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        // Ambil data yang sudah divalidasi oleh UpdateVehicleRequest
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
        
        // Hapus key 'gallery_images' dari array sebelum update
        unset($validatedData['gallery_images']);

        $vehicle->update($validatedData);

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $vehicle = Vehicle::with('images')->findOrFail($id);

        if ($vehicle->foto_utama) {
            Storage::delete(str_replace('/storage', 'public', $vehicle->foto_utama));
        }

        foreach ($vehicle->images as $image) {
            Storage::delete(str_replace('/storage', 'public', $image->path));
        }
        
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil dihapus!');
    }

    public function destroyImage(string $id)
    {
        $image = VehicleImage::findOrFail($id);
        
        Storage::delete(str_replace('/storage', 'public', $image->path));
        
        $image->delete();

        return back()->with('success', 'Gambar galeri berhasil dihapus.');
    }

    /**
     * Memperbarui status kendaraan secara spesifik.
     */
    /**
     * Memperbarui status kendaraan secara spesifik.
     */
    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        // Izinkan status 'disewa' di dalam validasi
        $request->validate([
            'status' => 'required|in:tersedia,servis,disewa',
        ]);

        $vehicle->update(['status' => $request->status]);

        return redirect()->route('admin.vehicles.index')->with('success', 'Status kendaraan berhasil diperbarui.');
    }
}