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
        // Corrigir campos obrigatórios para certificados
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('id');
            }
        });

        // Corrigir campos obrigatórios para quiz_attempts
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_attempts', 'attempt_number')) {
                $table->integer('attempt_number')->default(1)->after('quiz_id');
            }
        });

        // Corrigir campos obrigatórios para user_gamifications
        Schema::table('user_gamifications', function (Blueprint $table) {
            if (!Schema::hasColumn('user_gamifications', 'achievements_count')) {
                $table->integer('achievements_count')->default(0)->after('streak_days');
            }
        });

        // Corrigir campos obrigatórios para notifications
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'color')) {
                $table->string('color')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('color');
            }
            if (!Schema::hasColumn('notifications', 'metadata')) {
                $table->json('metadata')->nullable()->after('action_url');
            }
        });

        // Corrigir campos obrigatórios para activities (se a tabela existir)
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                if (!Schema::hasColumn('activities', 'action')) {
                    $table->string('action')->after('user_id');
                }
                if (!Schema::hasColumn('activities', 'data')) {
                    $table->json('data')->nullable()->after('action');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('certificate_number');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('attempt_number');
        });

        Schema::table('user_gamifications', function (Blueprint $table) {
            $table->dropColumn('achievements_count');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['color', 'action_url', 'metadata']);
        });

        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropColumn(['action', 'data']);
            });
        }
    }
};
