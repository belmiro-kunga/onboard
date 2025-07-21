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
        Schema::create('module_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('content_type', ['video', 'audio', 'pdf', 'image', 'text', 'html', 'interactive'])->default('text');
            $table->json('content_data')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('duration')->nullable(); // em segundos
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable(); // em bytes
            $table->string('mime_type')->nullable();
            $table->json('transcript')->nullable();
            $table->json('interactive_markers')->nullable();
            $table->boolean('notes_enabled')->default(true);
            $table->boolean('bookmarks_enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['module_id', 'order_index']);
            $table->index(['content_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_contents');
    }
};
