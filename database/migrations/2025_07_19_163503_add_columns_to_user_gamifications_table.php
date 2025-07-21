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
        Schema::table('user_gamifications', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->integer('total_points')->default(0)->after('user_id');
            $table->string('current_level')->default('Rookie')->after('total_points');
            $table->integer('rank_position')->nullable()->after('current_level');
            $table->integer('achievements_count')->default(0)->after('rank_position');
            $table->integer('streak_days')->default(0)->after('achievements_count');
            $table->integer('longest_streak')->default(0)->after('streak_days');
            $table->date('last_activity_date')->nullable()->after('longest_streak');
            $table->integer('level_progress')->default(0)->after('last_activity_date');
            $table->json('badges')->nullable()->after('level_progress');
            $table->json('statistics')->nullable()->after('badges');
            
            // Índices
            $table->unique('user_id');
            $table->index(['total_points', 'current_level']);
            $table->index('rank_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_gamifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['total_points', 'current_level']);
            $table->dropIndex(['rank_position']);
            $table->dropUnique(['user_id']);
            
            $table->dropColumn([
                'user_id',
                'total_points',
                'current_level',
                'rank_position',
                'achievements_count',
                'streak_days',
                'longest_streak',
                'last_activity_date',
                'level_progress',
                'badges',
                'statistics'
            ]);
        });
    }
};
