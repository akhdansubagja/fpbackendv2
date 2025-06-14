<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rental;
use App\Models\Payment;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 data rental, dan untuk setiap rental...
        Rental::factory(10)->create()->each(function ($rental) {
            // ...buatkan 1 data payment yang terhubung.
            Payment::factory()->create(['rental_id' => $rental->id]);
        });
    }
}