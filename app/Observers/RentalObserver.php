<?php

namespace App\Observers;

use App\Models\Rental;
use App\Models\User;
use App\Models\Notification;

class RentalObserver
{
    /**
     * Handle the Rental "created" event.
     */
    public function created(Rental $rental): void
    {
        // Cari semua user yang rolenya admin
        $admins = User::where('role', 'admin')->get();

        // Buat notifikasi untuk setiap admin
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pemesanan Baru Diterima',
                'message' => "Pemesanan baru dengan ID #{$rental->id} telah dibuat oleh {$rental->user->name}.",
                // --- TAMBAHAN: Sertakan link ke detail pesanan ---
                'link' => route('admin.rentals.show', $rental->id)
            ]);
        }
    }

    // ... (sisa method tidak perlu diubah) ...
    public function updated(Rental $rental): void { }
    public function deleted(Rental $rental): void { }
    public function restored(Rental $rental): void { }
    public function forceDeleted(Rental $rental): void { }
}