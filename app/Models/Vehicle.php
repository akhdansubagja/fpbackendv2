<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk', // Diubah
        'nama',
        'transmisi',
        'jumlah_kursi', // Baru
        'bahan_bakar',  // Baru
        'has_ac',       // Baru
        'harga_sewa_harian',
        'deskripsi',
        'foto_utama',   // Diubah
        'status',
    ];

    // --- RELASI ---

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Vehicle memiliki banyak gambar galeri.
     */
    public function images() // <-- Relasi Baru
    {
        return $this->hasMany(VehicleImage::class);
    }
}