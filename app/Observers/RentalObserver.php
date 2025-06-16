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
                'user_id' => $admin->id, // Notifikasi ini untuk admin
                'title' => 'Pemesanan Baru Diterima',
                'message' => "Pemesanan baru dengan ID #{$rental->id} telah dibuat oleh {$rental->user->name}.",
            ]);
        }
    }

    /**
     * Handle the Rental "updated" event.
     */
    public function updated(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "deleted" event.
     */
    public function deleted(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "restored" event.
     */
    public function restored(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "force deleted" event.
     */
    public function forceDeleted(Rental $rental): void
    {
        //
    }
}
