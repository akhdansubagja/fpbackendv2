<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

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
     * Memperbarui status pemesanan.
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        $request->validate([
            'status_pemesanan' => 'required|in:dikonfirmasi,ditolak,selesai',
        ]);

        $rental->update([
            'status_pemesanan' => $request->status_pemesanan,
        ]);

        return redirect()->route('admin.rentals.index')->with('success', 'Status pemesanan berhasil diperbarui!');
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
}