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
        Schema::table('notifications', function (Blueprint $table) {
            // Índice para consultas por usuário e status de leitura
            $table->index(['user_id', 'read_at'], 'notifications_user_read_index');
            
            // Índice para consultas por usuário e data de criação
            $table->index(['user_id', 'created_at'], 'notifications_user_created_index');
            
            // Índice para consultas por tipo de notificação
            $table->index('type', 'notifications_type_index');
            
            // Índice para consultas por data de criação (para limpeza de notificações antigas)
            $table->index('created_at', 'notifications_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_index');
            $table->dropIndex('notifications_user_created_index');
            $table->dropIndex('notifications_type_index');
            $table->dropIndex('notifications_created_at_index');
        });
    }
};
