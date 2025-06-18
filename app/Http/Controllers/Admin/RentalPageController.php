<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- KUNCI PERBAIKAN: TAMBAHKAN BARIS INI
use App\Models\Vehicle; // Menambahkan ini untuk kejelasan

class RentalPageController extends Controller
{
    /**
     * Menampilkan halaman daftar pemesanan.
     */
    public function index()
    {
        // Eager load relasi untuk efisiensi
        $rentals = Rental::with(['user', 'vehicle'])->latest()->get();
        return view('admin.rentals.index', ['rentals' => $rentals]);
    }

    /**
     * Menampilkan halaman detail satu pemesanan.
     */
    public function show(Rental $rental)
    {
        // Eager load semua relasi yang dibutuhkan oleh view
        $rental->load(['user', 'vehicle', 'payment']);
        
        return view('admin.rentals.show', ['rental' => $rental]);
    }

    /**
     * Memperbarui status pemesanan.
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        // Validasi sekarang akan berjalan dengan benar untuk semua status
        $validated = $request->validate([
            'status_pemesanan' => ['required', Rule::in(['pending', 'dikonfirmasi', 'berjalan', 'selesai', 'ditolak', 'dibatalkan'])]
        ]);

        // Update status pemesanan
        $rental->update($validated);

        // --- Logika Otomatisasi Status Mobil ---
        
        // Jika pesanan sedang berjalan, status mobil menjadi 'disewa'
        if ($validated['status_pemesanan'] == 'berjalan') {
            $rental->vehicle()->update(['status' => 'disewa']);
        }

        // Jika pesanan selesai atau ditolak/dibatalkan, kembalikan status mobil menjadi 'tersedia'
        if (in_array($validated['status_pemesanan'], ['selesai', 'ditolak', 'dibatalkan'])) {
            $rental->vehicle()->update(['status' => 'tersedia']);
        }

        return redirect()->route('admin.rentals.index')->with('success', 'Status pemesanan berhasil diperbarui!');
    }
}