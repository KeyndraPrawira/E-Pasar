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
        Schema::table('email_otps', function (Blueprint $table) {
            if (!Schema::hasColumn('email_otps', 'purpose')) {
                $table->string('purpose')
                    ->default('driver_registration')
                    ->after('email');
            }

            if (!Schema::hasColumn('email_otps', 'used_at')) {
                $table->timestamp('used_at')
                    ->nullable()
                    ->after('expired_at');
            }

            $table->index(['email', 'purpose']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_otps', function (Blueprint $table) {
            $table->dropIndex(['email', 'purpose']);

            if (Schema::hasColumn('email_otps', 'used_at')) {
                $table->dropColumn('used_at');
            }

            if (Schema::hasColumn('email_otps', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};
