<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import semua controller yang dibutuhkan
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\AuthController as UserAuthController;
use App\Http\Controllers\Api\VehicleController as UserVehicleController; // <-- Import controller kendaraan untuk user
use App\Http\Controllers\Api\RentalController as UserRentalController;
use App\Http\Controllers\Api\PaymentController as UserPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route default dari Laravel
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// == RUTE PUBLIK (Tidak Perlu Login) ==
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Rute untuk melihat semua kendaraan yang tersedia
Route::get('/vehicles', [UserVehicleController::class, 'index']);
// Rute untuk melihat detail satu kendaraan spesifik
Route::get('/vehicles/{id}', [UserVehicleController::class, 'show']);

// Rute untuk melihat semua kendaraan yang tersedia
Route::get('/vehicles', [UserVehicleController::class, 'index']);

Route::get('/vehicles/{vehicle}/booked-dates', [UserVehicleController::class, 'getBookedDates']);


// ==============================================
// == GRUP ROUTE UNTUK USER YANG SUDAH LOGIN ==
// ==============================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);

    // Route untuk mengganti password
    Route::post('/change-password', [UserAuthController::class, 'changePassword']);

    // Route untuk membuat pemesanan baru
    Route::post('/rentals', [UserRentalController::class, 'store']);

    // Route baru untuk melihat riwayat pemesanan milik user yang sedang login
    Route::get('/rentals/history', [UserRentalController::class, 'history']);

    // Route baru untuk unggah bukti pembayaran
    Route::post('/payments/{payment}/upload-proof', [UserPaymentController::class, 'uploadProof']);
    // Nanti route untuk membuat pesanan, riwayat, dll, akan diletakkan di sini

    Route::get('/rentals/{rental}/status', [UserRentalController::class, 'checkStatus']);
});


// ==============================================
// == GRUP ROUTE ADMIN YANG DILINDUNGI ==
// ==============================================
Route::prefix('admin')->middleware('auth:sanctum')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    
    Route::resource('vehicles', AdminVehicleController::class); // Menggunakan AdminVehicleController
    
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::patch('/rentals/{rental}/update-status', [RentalController::class, 'updateStatus']);
    
    Route::patch('/payments/{payment}/update-status', [PaymentController::class, 'updateStatus']);
    
    Route::get('/dashboard', [DashboardController::class, 'summary']);
});