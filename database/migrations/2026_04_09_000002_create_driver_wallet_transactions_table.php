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
        Schema::create('driver_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_wallet_id')
                ->constrained('driver_wallets')
                ->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit', 'adjustment']);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('balance_before');
            $table->unsignedBigInteger('balance_after');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['driver_wallet_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_wallet_transactions');
    }
};
