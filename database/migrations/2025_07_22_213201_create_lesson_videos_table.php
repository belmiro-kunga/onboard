<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['youtube', 'local', 'vimeo'])->default('local');
            $table->string('video_url')->nullable();
            $table->string('video_id')->nullable();
            $table->string('file_path')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('quality')->nullable();
            $table->json('subtitles')->nullable();
            $table->json('chapters')->nullable();
            $table->boolean('auto_play_next')->default(false);
            $table->boolean('picture_in_picture')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['lesson_id']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_videos');
    }
};