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
        Schema::table('modules', function (Blueprint $table) {
            // Adicionar relacionamento com curso
            $table->foreignId('course_id')->nullable()->after('id')->constrained('courses')->onDelete('cascade');
            
            // Adicionar campos para melhor organização
            $table->json('requirements')->nullable()->after('description'); // Pré-requisitos do módulo
            $table->integer('duration_minutes')->default(0)->after('requirements'); // Duração estimada
            
            // Melhorar indexação
            $table->index(['course_id', 'order_index']);
            $table->index(['is_active', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'requirements', 'duration_minutes']);
        });
    }
};