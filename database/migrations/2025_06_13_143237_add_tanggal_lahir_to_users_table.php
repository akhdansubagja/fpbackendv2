<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Gunakan Schema::table() untuk memodifikasi tabel yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom baru 'tanggal_lahir' setelah kolom 'path_sim'
            $table->date('tanggal_lahir')->nullable()->after('path_sim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Definisikan cara untuk mengembalikan (rollback) perubahan
            $table->dropColumn('tanggal_lahir');
        });
    }
};