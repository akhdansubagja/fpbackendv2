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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('merk'); // Diubah dari 'jenis'
            $table->string('nama');
            $table->enum('transmisi', ['manual', 'matic']);
            $table->integer('jumlah_kursi'); // Tambahan baru
            $table->enum('bahan_bakar', ['bensin', 'diesel', 'listrik', 'hybrid']); // Tambahan baru
            $table->boolean('has_ac')->default(true); // Tambahan baru
            $table->decimal('harga_sewa_harian', 10, 2);
            $table->text('deskripsi');
            $table->string('foto_utama'); // Diubah dari 'foto'
            $table->enum('status', ['tersedia', 'disewa', 'servis'])->default('tersedia');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
