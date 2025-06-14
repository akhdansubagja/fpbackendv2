<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $waktuSewa = fake()->dateTimeBetween('-1 month', '+1 month');
        $waktuKembali = (clone $waktuSewa)->modify('+' . rand(1, 7) . ' days');

        return [
            'user_id' => \App\Models\User::where('role', 'penyewa')->inRandomOrder()->first()->id,
            'vehicle_id' => \App\Models\Vehicle::inRandomOrder()->first()->id,
            'waktu_sewa' => $waktuSewa,
            'waktu_kembali' => $waktuKembali,
            'total_harga' => fake()->numberBetween(30, 100) * 10000,
            'status_pemesanan' => fake()->randomElement(['pending', 'dikonfirmasi', 'selesai']),
        ];
    }
}
