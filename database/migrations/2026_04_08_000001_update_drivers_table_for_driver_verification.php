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
        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])
                    ->default('pending')
                    ->after('foto_kendaraan');
            }

            if (!Schema::hasColumn('drivers', 'verification_notes')) {
                $table->text('verification_notes')
                    ->nullable()
                    ->after('status');
            }

            if (!Schema::hasColumn('drivers', 'verified_by')) {
                $table->foreignId('verified_by')
                    ->nullable()
                    ->after('verification_notes')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('drivers', 'verified_at')) {
                $table->timestamp('verified_at')
                    ->nullable()
                    ->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'verified_by')) {
                $table->dropConstrainedForeignId('verified_by');
            }

            if (Schema::hasColumn('drivers', 'verified_at')) {
                $table->dropColumn('verified_at');
            }

            if (Schema::hasColumn('drivers', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }

            if (Schema::hasColumn('drivers', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
