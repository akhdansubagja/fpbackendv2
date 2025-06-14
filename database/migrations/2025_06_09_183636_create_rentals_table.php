<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->dateTime('waktu_sewa'); // Diubah dari DATE ke DATETIME
            $table->dateTime('waktu_kembali'); // Diubah dari DATE ke DATETIME
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_pemesanan', ['pending', 'dikonfirmasi', 'ditolak', 'berjalan', 'selesai', 'dibatalkan'])->default('pending');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
