<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sender_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('receiver_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('application_id')
                ->nullable()
                ->constrained('applications')
                ->nullOnDelete();

            $table->foreignId('lease_id')
                ->nullable()
                ->constrained('leases')
                ->nullOnDelete();

            $table->string('subject')->nullable();
            $table->text('body');

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['receiver_id', 'read_at']);
            $table->index(['sender_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};