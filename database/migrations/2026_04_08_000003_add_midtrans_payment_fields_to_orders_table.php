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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')
                    ->nullable()
                    ->unique()
                    ->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'payment_token')) {
                $table->string('payment_token')
                    ->nullable()
                    ->after('payment_reference');
            }

            if (!Schema::hasColumn('orders', 'payment_url')) {
                $table->text('payment_url')
                    ->nullable()
                    ->after('payment_token');
            }

            if (!Schema::hasColumn('orders', 'payment_type')) {
                $table->string('payment_type')
                    ->nullable()
                    ->after('payment_url');
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')
                    ->nullable()
                    ->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }

            if (Schema::hasColumn('orders', 'payment_type')) {
                $table->dropColumn('payment_type');
            }

            if (Schema::hasColumn('orders', 'payment_url')) {
                $table->dropColumn('payment_url');
            }

            if (Schema::hasColumn('orders', 'payment_token')) {
                $table->dropColumn('payment_token');
            }

            if (Schema::hasColumn('orders', 'payment_reference')) {
                $table->dropUnique(['payment_reference']);
                $table->dropColumn('payment_reference');
            }
        });
    }
};
