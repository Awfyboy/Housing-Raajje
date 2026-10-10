<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('client')->index();
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->index();
            }

            if (!Schema::hasColumn('users', 'mfa_enabled')) {
                $table->boolean('mfa_enabled')->default(false);
            }

            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')
                    ->nullable()
                    ->constrained('branches')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Intentionally left empty to avoid deleting
        // columns that may have existed before this migration.
    }
};