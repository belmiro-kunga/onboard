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
        Schema::create('simulados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('categoria'); // technical, security, compliance, customer_service, etc.
            $table->enum('nivel', ['basic', 'intermediate', 'advanced']);
            $table->integer('duracao'); // em minutos
            $table->integer('questoes_count');
            $table->integer('passing_score'); // pontuação mínima para aprovação
            $table->integer('pontos_recompensa');
            $table->enum('status', ['draft', 'active', 'inactive'])->default('active');
            $table->json('configuracoes')->nullable(); // configurações adicionais
            $table->timestamp('disponivel_em')->nullable();
            $table->timestamp('expiracao_em')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['categoria', 'nivel']);
            $table->index(['status', 'disponivel_em']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulados');
    }
};
