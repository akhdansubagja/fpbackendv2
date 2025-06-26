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
        Schema::table('payments', function (Blueprint $table) {
            // Jumlah deposit yang benar-benar dikembalikan ke user
            $table->decimal('deposit_dikembalikan', 15, 2)->default(0)->after('status_deposit');
            // Jumlah deposit yang dipotong (menjadi pendapatan)
            $table->decimal('deposit_dipotong', 15, 2)->default(0)->after('deposit_dikembalikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
