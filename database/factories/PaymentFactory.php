<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // rental_id akan kita isi dari seeder
            'security_deposit' => fake()->randomElement([0, 200000, 500000]),
            'status_deposit' => 'ditahan',
            'metode_pembayaran' => 'transfer',
            'jumlah_bayar' => fake()->numberBetween(30, 100) * 10000,
            'tanggal_pembayaran' => now(),
            'bukti_pembayaran' => 'payments/dummy_proof.jpg',
            'status_pembayaran' => fake()->randomElement(['pending', 'lunas']),
        ];
    }
}
