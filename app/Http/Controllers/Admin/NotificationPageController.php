<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class NotificationPageController extends Controller
{
    public function index()
    {
        // Logika ini kembali seperti semula, hanya mengambil notifikasi
        $notifications = Auth::user()->notifications()->latest()->get();

        // Logika "update is_read" kita HAPUS dari sini
        return view('admin.notifications.index', ['notifications' => $notifications]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
        return response()->json(['unread_count' => $unreadCount]);
    }

    // --- METHOD BARU: Untuk membaca notifikasi & redirect ---
    public function readAndRedirect(Notification $notification)
    {
        // Pastikan admin hanya bisa mengakses notifikasinya sendiri
        if (Auth::id() !== $notification->user_id) {
            abort(403);
        }

        // Tandai sebagai sudah dibaca
        $notification->update(['is_read' => true]);

        // Jika ada link, redirect ke sana. Jika tidak, redirect ke halaman notifikasi.
        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->route('admin.notifications.index');
    }
    // --- AKHIR METHOD BARU ---
}