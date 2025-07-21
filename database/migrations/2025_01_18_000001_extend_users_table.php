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
            // Campos adicionais para o sistema de onboarding HCP
            $table->string('department')->nullable()->after('email');
            $table->string('position')->nullable()->after('department');
            $table->string('avatar')->nullable()->after('position');
            $table->enum('role', ['admin', 'manager', 'employee'])->default('employee')->after('avatar');
            $table->boolean('is_active')->default(true)->after('role');
            $table->date('hire_date')->nullable()->after('is_active');
            $table->string('phone')->nullable()->after('hire_date');
            
            // Campos para autenticação de dois fatores
            $table->boolean('two_factor_enabled')->default(false)->after('phone');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            
            // Preferências do usuário (JSON)
            $table->json('preferences')->nullable()->after('two_factor_recovery_codes');
            
            // Índices para performance
            $table->index(['department', 'is_active']);
            $table->index(['role', 'is_active']);
            $table->index('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['department', 'is_active']);
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['hire_date']);
            
            $table->dropColumn([
                'department',
                'position',
                'avatar',
                'role',
                'is_active',
                'hire_date',
                'phone',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'preferences',
            ]);
        });
    }
};