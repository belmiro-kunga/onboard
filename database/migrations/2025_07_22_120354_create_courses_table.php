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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('requirements')->nullable(); // Pré-requisitos
            $table->integer('duration_hours')->default(0); // Duração estimada em horas
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('type', ['mandatory', 'optional', 'certification'])->default('optional');
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->json('tags')->nullable(); // Tags para categorização
            $table->timestamps();
            
            $table->index(['is_active', 'order_index']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};