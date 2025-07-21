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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['module_deadline', 'quiz_reminder', 'meeting', 'training', 'review', 'custom']);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->boolean('all_day')->default(false);
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable(); // Para reuniões online
            $table->json('attendees')->nullable(); // Lista de participantes
            $table->json('reminders')->nullable(); // Configurações de lembrete
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->string('external_event_id')->nullable(); // ID do evento no calendário externo
            $table->string('calendar_provider')->nullable(); // google, outlook, etc.
            $table->json('metadata')->nullable(); // Dados adicionais
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'start_time']);
            $table->index(['type', 'status']);
            $table->index('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};