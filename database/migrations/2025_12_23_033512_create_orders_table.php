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
        // =============================================
        // TABEL ORDERS
        // =============================================
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'menunggu_driver',
                'dalam_proses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ])->default('dalam_proses');

            $table->enum('metode_pembayaran', [
                'cod',
                'midtrans',
            ])->nullable();

            $table->text('alamat_pengiriman');
            $table->decimal('latitude', 12, 8);
            $table->decimal('longitude', 12, 8);
            $table->decimal('jarak_km', 5, 2);

            // Pakai unsignedBigInteger agar aman untuk nilai Rupiah besar
            $table->unsignedBigInteger('total_harga_barang');
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total_harga');

            $table->text('catatan')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Index untuk filter & query performa
            $table->index('status');
            $table->index('buyer_id');
            $table->index('driver_id');
        });

        // =============================================
        // TABEL ORDER DETAILS
        // =============================================
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('kios_id');

             $table->enum('status', [
                'pending',
                'pending_request',      // kirim request ganti ke user
                'diambil',      // sudah diambil
                'tidak_ada',    // stok habis
                'diganti'       // diganti produk lain
            ])->default('pending');

             $table->unsignedBigInteger('produk_pengganti_id')->nullable();

            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('subtotal_harga');

            $table->text('catatan_driver')->nullable();

            $table->foreign('produk_id')
                ->references('id')
                ->on('produks')
                ->cascadeOnDelete();
            
            $table->foreign('produk_pengganti_id')
            ->references('id')
            ->on('produks')
            ->nullOnDelete();

            $table->foreign('kios_id')
                ->references('id')
                ->on('kios')
                ->cascadeOnDelete();

            $table->timestamps();

            // Composite index untuk query per order & kios
            $table->index(['order_id', 'kios_id']);
            $table->index('produk_id');
        });

        // =============================================
        // TABEL ORDER HISTORIES (Riwayat Order)
        // Snapshot header order saat status = 'selesai'
        // =============================================
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Snapshot data order (disimpan agar tidak berubah walau order diedit)
            $table->string('kode_pesanan');
            $table->text('alamat_pengiriman');
            $table->decimal('latitude', 12, 8);
            $table->decimal('longitude', 12, 8);
            $table->decimal('jarak_km', 5, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->unsignedBigInteger('total_harga_barang');
            $table->unsignedBigInteger('ongkir');
            $table->unsignedBigInteger('total_harga');
            $table->text('catatan')->nullable();

            // Waktu order selesai
            $table->timestamp('selesai_at')->nullable();

            $table->timestamps();

            $table->index('buyer_id');
            $table->index('driver_id');
        });

        // =============================================
        // TABEL ORDER HISTORY DETAILS
        // Snapshot produk saat order selesai
        // Pakai string (bukan foreignId) agar data tidak berubah
        // walau produk/kios dihapus atau diedit
        // =============================================
        Schema::create('order_history_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_history_id')
                ->constrained('order_histories')
                ->cascadeOnDelete();

            // Snapshot — disimpan sebagai string bukan foreign key
            $table->string('nama_produk');
            $table->string('nama_kios');

            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('subtotal_harga');
            $table->enum('status', [
                'diambil',      // sudah diambil
                'tidak_ada',    // stok habis
            ])->default('diambil');

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index('order_history_id');
        });

        // =============================================
        // TABEL ORDER CHATS
        // =============================================
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('sender_role', ['buyer', 'driver']);

            $table->text('message')->nullable();

            $table->string('attachment')->nullable();

            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->index(['order_id', 'sender_id']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_chats');
        Schema::dropIfExists('order_history_details');
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};