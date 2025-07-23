<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('lesson_comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('video_timestamp')->nullable();
            $table->boolean('is_question')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->integer('likes_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['lesson_id', 'created_at']);
            $table->index(['parent_id']);
            $table->index(['is_question', 'is_resolved']);
            $table->index(['video_timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_comments');
    }
};