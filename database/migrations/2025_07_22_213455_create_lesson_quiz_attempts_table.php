<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('questions_data');
            $table->json('answers')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('time_limit_minutes')->nullable();
            $table->integer('time_spent_seconds')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['lesson_quiz_id', 'user_id']);
            $table->index(['user_id', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_quiz_attempts');
    }
};