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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('reportable_type'); // produk, kios, driver
            $table->unsignedBigInteger('reportable_id');

            $table->foreignId('kategori_laporan_id')->constrained()->onDelete('cascade');

            $table->text('alasan');
            $table->string('status')->default('pending'); // pending, diproses, selesai

            $table->timestamps();

            $table->index(['reportable_type', 'reportable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            //
        });
    }
};
