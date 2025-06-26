<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController; // <-- Tambahkan ini
use App\Http\Controllers\Admin\DashboardController; // <-- Tambahkan di atas
use App\Http\Controllers\Admin\VehiclePageController; // <-- Tambahkan ini di atas
use App\Http\Controllers\Admin\RentalPageController; // <-- Tambahkan ini di atas
use App\Http\Controllers\Admin\PaymentPageController; // <-- Tambahkan ini di atas
use App\Http\Controllers\Admin\NotificationPageController; // <-- Tambahkan di atas


Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman login admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Route untuk memproses data login (POST)
    Route::post('/login', [LoginController::class, 'login']);

    // Grup route yang memerlukan login admin
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('vehicles', VehiclePageController::class)->except(['show']); // Menggunakan except() lebih rapi
        // Route baru khusus untuk update status kendaraan
        Route::patch('/vehicles/{vehicle}/update-status', [VehiclePageController::class, 'updateStatus'])->name('vehicles.updateStatus');
        Route::delete('/gallery-images/{id}', [VehiclePageController::class, 'destroyImage'])->name('vehicles.destroyImage');

        // Route untuk halaman manajemen rental
        Route::get('/rentals', [RentalPageController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/{rental}', [RentalPageController::class, 'show'])->name('rentals.show'); // <-- TAMBAHKAN INI
        Route::patch('/rentals/{rental}/update-status', [RentalPageController::class, 'updateStatus'])->name('rentals.update-status');

        // Route untuk halaman manajemen pembayaran
        Route::get('/payments', [PaymentPageController::class, 'index'])->name('payments.index');
        Route::patch('/payments/{payment}/update-status', [PaymentPageController::class, 'updateStatus'])->name('payments.update-status');
        // --- TAMBAHKAN ROUTE BARU DI BAWAH INI ---
        Route::patch('/payments/{payment}/update-deposit', [PaymentPageController::class, 'updateDeposit'])->name('payments.update-deposit');

        // Route untuk halaman notifikasi
        Route::get('/notifications', [NotificationPageController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread-count', [NotificationPageController::class, 'getUnreadCount'])->name('notifications.unreadCount');
        Route::get('/notifications/{notification}/read', [NotificationPageController::class, 'readAndRedirect'])->name('notifications.read');
        // --- AKHIR TAMBAHAN ---
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // <-- Tambahkan ini
});