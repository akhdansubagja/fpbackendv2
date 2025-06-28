<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserPageController extends Controller
{
    /**
     * Menampilkan halaman daftar semua pengguna (penyewa).
     */
    public function index()
    {
        // Ambil semua user dengan role 'penyewa', urutkan dari yang terbaru
        $users = User::where('role', 'penyewa')->latest()->paginate(10); // Paginate untuk 10 user per halaman
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan halaman detail untuk satu pengguna.
     */
    public function show(User $user)
    {
        // Laravel akan otomatis mencari user berdasarkan ID dari URL
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menghapus data pengguna dari database.
     */
    public function destroy(User $user)
    {
        // Hapus semua data terkait jika ada (misal: file SIM)
        Storage::delete('public/' . $user->path_sim); // Aktifkan jika ingin hapus file juga

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}