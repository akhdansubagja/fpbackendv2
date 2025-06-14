<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 1 Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'nomor_telepon' => '081234567890',
            'path_sim' => 'sims/admin_sim.jpg',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kantor Pusat',
            'role' => 'admin',
        ]);

        // Membuat 9 Penyewa menggunakan Factory
        User::factory(9)->create();
    }
}