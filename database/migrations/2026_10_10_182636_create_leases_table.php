<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();

            $table->string('lease_number')->unique();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->restrictOnDelete();

            $table->foreignId('owner_id')
                ->constrained('owners')
                ->restrictOnDelete();

            $table->foreignId('client_id')
                ->constrained('clients')
                ->restrictOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->restrictOnDelete();

            $table->foreignId('application_id')
                ->unique()
                ->nullable()
                ->constrained('applications')
                ->restrictOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->decimal('advance_payment', 10, 2)->default(0);

            $table->timestamp('owner_signed_at')->nullable();
            $table->timestamp('client_signed_at')->nullable();
            $table->string('signed_document_path')->nullable();

            $table->string('status')->default('draft')->index();

            $table->text('termination_reason')->nullable();
            $table->timestamp('terminated_at')->nullable();

            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index(['owner_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};