<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_view_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_video_id')->constrained()->onDelete('cascade');
            $table->integer('watch_time_seconds')->default(0);
            $table->integer('current_time_seconds')->default(0);
            $table->decimal('playback_speed', 3, 2)->default(1.00);
            $table->timestamp('last_watched_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_video_id']);
            $table->index(['lesson_video_id']);
            $table->index(['last_watched_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_view_progress');
    }
};