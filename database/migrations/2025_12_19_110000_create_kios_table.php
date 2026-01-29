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
        Schema::create('kios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasar_id');
            $table->string('nama_kios');
            $table->text('lokasi');
            $table->unsignedBigInteger('user_id');
           
            $table->text('deskripsi')->nullable();
            $table->string('foto_kios')->nullable();

            $table->foreign('pasar_id')->references('id')->on('pasars')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kios');
    }
};
