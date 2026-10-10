<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viewing_feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('viewing_id')
                ->unique()
                ->constrained('viewings')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('interested')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viewing_feedback');
    }
};