<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['pdf', 'doc', 'docx', 'slide', 'link', 'image', 'video', 'audio'])->default('pdf');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_downloadable')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['lesson_id', 'order_index']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_materials');
    }
};