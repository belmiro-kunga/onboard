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
        Schema::table('users', function (Blueprint $table) {
            // Remover campos desnecessários se existirem
            $columnsToRemove = [
                'avatar',
                'bio', 
                'birthdate',
                'hire_date',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'preferences',
                'last_login_at'
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Remover índices desnecessários se existirem
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('users');
            
            if (array_key_exists('users_department_is_active_index', $indexesFound)) {
                $table->dropIndex(['department', 'is_active']);
            }
            
            if (array_key_exists('users_hire_date_index', $indexesFound)) {
                $table->dropIndex(['hire_date']);
            }

            // Manter apenas o índice essencial
            if (!Schema::hasIndex('users', ['role', 'is_active'])) {
                $table->index(['role', 'is_active']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recriar campos removidos (caso necessário para rollback)
            $table->string('avatar')->nullable()->after('position');
            $table->text('bio')->nullable()->after('phone');
            $table->date('birthdate')->nullable()->after('phone');
            $table->date('hire_date')->nullable()->after('is_active');
            $table->boolean('two_factor_enabled')->default(false)->after('phone');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->json('preferences')->nullable()->after('two_factor_recovery_codes');
            $table->timestamp('last_login_at')->nullable()->after('preferences');

            // Recriar índices
            $table->index(['department', 'is_active']);
            $table->index(['hire_date']);
        });
    }
};