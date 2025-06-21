<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk',
        'nama',
        'transmisi',
        'jumlah_kursi',
        'bahan_bakar',
        'has_ac',
        'harga_sewa_harian',
        'security_deposit', // <-- TAMBAHKAN INI
        'deskripsi',
        'foto_utama',
        'status',
    ];

    // --- RELASI ---

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    
    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }
}