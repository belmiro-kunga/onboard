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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->enum('category', ['learning', 'engagement', 'performance', 'social', 'special'])->default('learning');
            $table->enum('type', ['modules_completed', 'points_earned', 'quiz_streak', 'perfect_score', 'time_spent', 'login_streak', 'first_module', 'speed_demon', 'knowledge_seeker', 'social_butterfly'])->default('modules_completed');
            $table->json('condition_data')->nullable();
            $table->integer('points_reward')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])->default('common');
            $table->string('unlock_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['category', 'is_active']);
            $table->index(['type', 'is_active']);
            $table->index('rarity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
