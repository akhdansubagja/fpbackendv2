<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationPageController extends Controller
{
    public function index()
    {
        // Ambil notifikasi milik admin yang sedang login
        $notifications = Auth::user()->notifications()->latest()->get();

        return view('admin.notifications.index', ['notifications' => $notifications]);
    }
}