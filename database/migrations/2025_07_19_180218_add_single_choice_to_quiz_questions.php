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
        Schema::table('quiz_questions', function (Blueprint $table) {
            // Alterar o enum para incluir single_choice
            $table->enum('question_type', ['multiple_choice', 'single_choice', 'true_false', 'drag_drop', 'fill_blank'])->default('multiple_choice')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            // Reverter para o enum original
            $table->enum('question_type', ['multiple_choice', 'true_false', 'drag_drop', 'fill_blank'])->default('multiple_choice')->change();
        });
    }
};
