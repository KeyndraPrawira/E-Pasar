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
        Schema::create('driver_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_wallet_id')
                ->constrained('driver_wallets')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number', 50);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('requested_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('transfer_reference')->nullable();
            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['driver_wallet_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_withdrawals');
    }
};
