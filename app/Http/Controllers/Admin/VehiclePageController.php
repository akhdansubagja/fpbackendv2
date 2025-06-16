<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiclePageController extends Controller
{
    /**
     * Menampilkan halaman daftar kendaraan.
     */
    public function index()
    {
        $vehicles = Vehicle::latest()->get();
        return view('admin.vehicles.index', ['vehicles' => $vehicles]);
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Menyimpan data kendaraan baru dari form.
     */
    public function store(Request $request)
    {
        // Validasi data (untuk simple, kita bisa letakkan di sini)
        $validatedData = $request->validate([
            'merk' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'transmisi' => 'required|in:manual,matic',
            'jumlah_kursi' => 'required|integer|min:1',
            'bahan_bakar' => 'required|in:bensin,diesel,listrik,hybrid',
            'has_ac' => 'required|boolean',
            'harga_sewa_harian' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Tambahkan status default
        $validatedData['status'] = 'tersedia';

        // Proses upload file
        if ($request->hasFile('foto_utama')) {
            $path = $request->file('foto_utama')->store('public/vehicles/main');
            $validatedData['foto_utama'] = Storage::url($path);
        }

        // Simpan ke database
        Vehicle::create($validatedData);

        // Arahkan kembali ke halaman daftar kendaraan dengan pesan sukses
        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kendaraan.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('admin.vehicles.edit', ['vehicle' => $vehicle]);
    }

    /**
     * Memperbarui data kendaraan di database.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validatedData = $request->validate([
            'merk' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'transmisi' => 'required|in:manual,matic',
            'jumlah_kursi' => 'required|integer|min:1',
            'bahan_bakar' => 'required|in:bensin,diesel,listrik,hybrid',
            'has_ac' => 'required|boolean',
            'harga_sewa_harian' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Foto tidak wajib diisi saat update
        ]);

        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama
            if ($vehicle->foto_utama) {
                Storage::delete(str_replace('/storage', 'public', $vehicle->foto_utama));
            }
            // Simpan foto baru
            $path = $request->file('foto_utama')->store('public/vehicles/main');
            $validatedData['foto_utama'] = Storage::url($path);
        }

        $vehicle->update($validatedData);

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    /**
     * Menghapus data kendaraan dari database.
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
        
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil dihapus!');
    }
}