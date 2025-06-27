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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom baru setelah kolom 'alamat'
            // Dibuat nullable() agar pengguna lama tidak error
            $table->string('nomor_rekening', 50)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migration di-rollback
            $table->dropColumn('nomor_rekening');
        });
    }
};