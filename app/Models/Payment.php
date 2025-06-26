<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'security_deposit',     // Baru
        'status_deposit',       // Baru
        'deposit_dikembalikan', // <-- TAMBAHKAN INI
        'deposit_dipotong',     // <-- TAMBAHKAN INI
        'metode_pembayaran',
        'jumlah_bayar',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    // ... (Relasi tidak berubah) ...
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}