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
        Schema::table('rentals', function (Blueprint $table) {
            // Tambahkan kolom baru setelah 'total_harga'
            $table->enum('delivery_option', ['pickup', 'delivered'])->default('pickup')->after('total_harga');
            $table->text('delivery_address')->nullable()->after('delivery_option');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('delivery_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['delivery_option', 'delivery_address', 'delivery_fee']);
        });
    }
};