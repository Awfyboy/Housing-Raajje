<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lease_id')
                ->constrained('leases')
                ->restrictOnDelete();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('payment_type')->default('rent');
            $table->string('payment_method')->nullable();

            $table->string('status')->default('pending')->index();

            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->string('transaction_reference')->nullable()->unique();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['lease_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};