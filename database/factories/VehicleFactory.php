<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'merk' => fake()->randomElement(['Toyota', 'Honda', 'Suzuki', 'Mitsubishi', 'Daihatsu']), // Diubah dari 'jenis'
            'nama' => fake()->company(), // Menggunakan nama yang lebih relevan
            'transmisi' => fake()->randomElement(['manual', 'matic']),
            'jumlah_kursi' => fake()->randomElement([4, 5, 7, 8]), // Tambahan baru
            'bahan_bakar' => fake()->randomElement(['bensin', 'diesel', 'listrik', 'hybrid']), // Tambahan baru
            'has_ac' => fake()->boolean(90), // Tambahan baru, 90% kemungkinan true
            'harga_sewa_harian' => fake()->numberBetween(25, 70) * 10000, // Logika harga yg lebih realistis
            'deskripsi' => fake()->paragraph(),
            'foto_utama' => 'vehicles/dummy_vehicle.jpg', // Diubah dari 'foto'
            'status' => 'tersedia',
        ];
    }
}