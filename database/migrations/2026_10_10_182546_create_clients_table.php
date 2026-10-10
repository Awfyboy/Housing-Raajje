<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();

            $table->decimal('maximum_rent', 10, 2)->nullable();
            $table->string('preferred_location')->nullable();
            $table->unsignedTinyInteger('preferred_rooms')->nullable();
            $table->unsignedSmallInteger('preferred_lease')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};