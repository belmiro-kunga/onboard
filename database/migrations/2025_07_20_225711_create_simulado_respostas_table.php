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
        Schema::create('simulado_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentativa_id')->constrained('simulado_tentativas')->onDelete('cascade');
            $table->foreignId('questao_id')->constrained('simulado_questoes')->onDelete('cascade');
            $table->string('resposta_usuario');
            $table->boolean('correta')->default(false);
            $table->integer('tempo_questao')->default(0); // tempo gasto na questão em segundos
            $table->timestamp('respondida_em')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['tentativa_id', 'questao_id']);
            $table->index(['tentativa_id', 'correta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulado_respostas');
    }
};
