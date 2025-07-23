<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'reflection', 'activity'])->default('quiz');
            $table->json('questions');
            $table->integer('time_limit_minutes')->nullable();
            $table->integer('max_attempts')->nullable();
            $table->decimal('passing_score', 5, 2)->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['lesson_id']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_quizzes');
    }
};