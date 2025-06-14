<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'waktu_sewa',       // Diubah
        'waktu_kembali',    // Diubah
        'total_harga',
        'status_pemesanan',
    ];

    // ... (Relasi tidak berubah) ...
    public function user() { return $this->belongsTo(User::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function payment() { return $this->hasOne(Payment::class); }
}