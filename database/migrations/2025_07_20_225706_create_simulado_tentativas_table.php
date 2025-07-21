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
        Schema::create('simulado_tentativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('simulado_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0); // pontuação final
            $table->integer('tempo_gasto')->default(0); // em segundos
            $table->enum('status', ['in_progress', 'completed', 'abandoned', 'paused'])->default('in_progress');
            $table->timestamp('iniciado_em');
            $table->timestamp('finalizado_em')->nullable();
            $table->timestamp('pausado_em')->nullable();
            $table->json('metadados')->nullable(); // dados adicionais
            $table->timestamps();

            // Índices
            $table->index(['user_id', 'simulado_id']);
            $table->index(['status', 'iniciado_em']);
            $table->index(['simulado_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulado_tentativas');
    }
};
