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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('kode_pesanan')->unique();
    $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('driver_id')->nullable()->constrained('users')->cascadeOnDelete();
    $table->integer('ongkir')->default(0);        // total_harga + ongkir
    $table->enum('status', ['dalam_proses', 'dikirim', 'selesai'])->default('dalam_proses');
    $table->string('metode_pembayaran')->nullable();
    $table->text('alamat_pengiriman');
    $table->decimal('latitude', 12, 8);
    $table->decimal('longitude', 12, 8);
    $table->decimal('jarak_km', 5, 2);
    $table->integer('total_harga'); 
    $table->softDeletes();
    $table->timestamps();

    $table->index(['status']);

});
         Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('produk_id');
            $table->integer('jumlah');
            $table->integer('subtotal_harga');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });

        
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('message');

            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
