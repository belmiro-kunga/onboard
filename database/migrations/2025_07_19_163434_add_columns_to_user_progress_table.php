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
        Schema::table('user_progress', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('module_id')->constrained()->onDelete('cascade')->after('user_id');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'paused'])->default('not_started')->after('module_id');
            $table->integer('progress_percentage')->default(0)->after('status');
            $table->timestamp('started_at')->nullable()->after('progress_percentage');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->integer('time_spent')->default(0)->after('completed_at'); // em minutos
            $table->timestamp('last_accessed_at')->nullable()->after('time_spent');
            $table->string('current_section')->nullable()->after('last_accessed_at');
            $table->json('notes')->nullable()->after('current_section');
            $table->json('bookmarks')->nullable()->after('notes');
            
            // Índices
            $table->unique(['user_id', 'module_id']);
            $table->index(['user_id', 'status']);
            $table->index(['module_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'module_id']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['module_id', 'status']);
            $table->dropUnique(['user_id', 'module_id']);
            
            $table->dropColumn([
                'user_id',
                'module_id',
                'status',
                'progress_percentage',
                'started_at',
                'completed_at',
                'time_spent',
                'last_accessed_at',
                'current_section',
                'notes',
                'bookmarks'
            ]);
        });
    }
};
