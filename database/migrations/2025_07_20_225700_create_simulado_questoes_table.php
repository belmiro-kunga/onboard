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
        Schema::create('simulado_questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulado_id')->constrained()->onDelete('cascade');
            $table->text('questao');
            $table->enum('tipo', ['multiple_choice', 'single_choice', 'true_false']);
            $table->json('opcoes'); // array de opções
            $table->string('resposta_correta');
            $table->text('explicacao')->nullable(); // explicação da resposta correta
            $table->integer('pontos')->default(1); // pontos por questão
            $table->integer('ordem')->default(0); // ordem das questões
            $table->boolean('ativa')->default(true);
            $table->timestamps();

            // Índices
            $table->index(['simulado_id', 'ordem']);
            $table->index(['simulado_id', 'ativa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulado_questoes');
    }
};
