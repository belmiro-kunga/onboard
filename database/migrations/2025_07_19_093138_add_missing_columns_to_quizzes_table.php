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
        Schema::table('quizzes', function (Blueprint $table) {
            // Verificar se as colunas não existem antes de adicionar
            if (!Schema::hasColumn('quizzes', 'module_id')) {
                $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('quizzes', 'title')) {
                $table->string('title')->after('module_id');
            }
            if (!Schema::hasColumn('quizzes', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('quizzes', 'instructions')) {
                $table->text('instructions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('quizzes', 'passing_score')) {
                $table->integer('passing_score')->default(70)->after('instructions');
            }
            if (!Schema::hasColumn('quizzes', 'max_attempts')) {
                $table->integer('max_attempts')->default(3)->after('passing_score');
            }
            if (!Schema::hasColumn('quizzes', 'time_limit')) {
                $table->integer('time_limit')->nullable()->after('max_attempts');
            }
            if (!Schema::hasColumn('quizzes', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('time_limit');
            }
            if (!Schema::hasColumn('quizzes', 'difficulty_level')) {
                $table->enum('difficulty_level', ['basic', 'intermediate', 'advanced'])->default('basic')->after('is_active');
            }
            if (!Schema::hasColumn('quizzes', 'category')) {
                $table->enum('category', ['hr', 'it', 'security', 'processes', 'culture', 'general'])->default('general')->after('difficulty_level');
            }
            if (!Schema::hasColumn('quizzes', 'points_reward')) {
                $table->integer('points_reward')->default(10)->after('category');
            }
            if (!Schema::hasColumn('quizzes', 'randomize_questions')) {
                $table->boolean('randomize_questions')->default(false)->after('points_reward');
            }
            if (!Schema::hasColumn('quizzes', 'show_results_immediately')) {
                $table->boolean('show_results_immediately')->default(true)->after('randomize_questions');
            }
            if (!Schema::hasColumn('quizzes', 'allow_review')) {
                $table->boolean('allow_review')->default(true)->after('show_results_immediately');
            }
            if (!Schema::hasColumn('quizzes', 'generate_certificate')) {
                $table->boolean('generate_certificate')->default(false)->after('allow_review');
            }
            if (!Schema::hasColumn('quizzes', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'module_id', 'title', 'description', 'instructions', 'passing_score',
                'max_attempts', 'time_limit', 'is_active', 'difficulty_level',
                'category', 'points_reward', 'randomize_questions',
                'show_results_immediately', 'allow_review', 'generate_certificate',
                'deleted_at'
            ]);
        });
    }
};