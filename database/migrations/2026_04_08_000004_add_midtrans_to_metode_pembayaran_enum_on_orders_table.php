<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'metode_pembayaran')) {
            DB::statement("ALTER TABLE orders MODIFY metode_pembayaran ENUM('cod', 'transfer', 'ewallet', 'midtrans') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'metode_pembayaran')) {
            DB::statement("ALTER TABLE orders MODIFY metode_pembayaran ENUM('cod', 'transfer', 'ewallet') NULL");
        }
    }
};
