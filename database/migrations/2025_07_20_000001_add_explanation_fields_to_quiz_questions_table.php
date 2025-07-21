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
            $table->text('explanation_correct')->nullable()->after('correct_answer');
            $table->json('explanation_incorrect')->nullable()->after('explanation_correct');
            $table->enum('feedback_type', ['immediate', 'delayed'])->default('immediate')->after('explanation_incorrect');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('explanation_correct');
            $table->dropColumn('explanation_incorrect');
            $table->dropColumn('feedback_type');
        });
    }
};