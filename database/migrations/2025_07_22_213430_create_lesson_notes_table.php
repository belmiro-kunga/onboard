<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->integer('video_timestamp')->nullable();
            $table->string('color', 20)->default('yellow');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'lesson_id']);
            $table->index(['video_timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_notes');
    }
};