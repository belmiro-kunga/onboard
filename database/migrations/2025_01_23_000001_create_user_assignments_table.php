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
        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('assignable_type'); // Course, Quiz, Module, etc.
            $table->unsignedBigInteger('assignable_id');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('assigned_at');
            $table->timestamp('due_date')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'overdue'])->default('assigned');
            $table->text('notes')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
            
            // Índices
            $table->index(['assignable_type', 'assignable_id']);
            $table->index(['user_id', 'status']);
            $table->index(['assigned_by']);
            $table->unique(['user_id', 'assignable_type', 'assignable_id'], 'user_assignment_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_assignments');
    }
};