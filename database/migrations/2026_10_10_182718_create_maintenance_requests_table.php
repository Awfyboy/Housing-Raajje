<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->restrictOnDelete();

            $table->foreignId('reported_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('assigned_staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            $table->foreignId('lease_id')
                ->nullable()
                ->constrained('leases')
                ->nullOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('priority')->default('medium')->index();
            $table->string('status')->default('open')->index();

            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['assigned_staff_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};