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
        Schema::table('certificates', function (Blueprint $table) {
            // Tornar quiz_id e quiz_attempt_id opcionais para suportar outros tipos de certificados
            $table->foreignId('quiz_id')->nullable()->change();
            $table->foreignId('quiz_attempt_id')->nullable()->change();
            
            // Verificar se a coluna score existe antes de tentar modificá-la
            if (Schema::hasColumn('certificates', 'score')) {
                $table->integer('score')->nullable()->change();
            }
            
            // Adicionar novos campos apenas se não existirem
            if (!Schema::hasColumn('certificates', 'type')) {
                $table->string('type')->default('quiz')->after('user_id'); // quiz, module, category, overall, excellence, speed
            }
            if (!Schema::hasColumn('certificates', 'title')) {
                $table->string('title')->after('type');
            }
            if (!Schema::hasColumn('certificates', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('certificates', 'category')) {
                $table->string('category')->nullable()->after('description');
            }
            if (!Schema::hasColumn('certificates', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable()->after('category'); // ID do módulo, categoria, etc.
            }
            if (!Schema::hasColumn('certificates', 'verification_code')) {
                $table->string('verification_code')->unique()->after('certificate_number');
            }
            if (!Schema::hasColumn('certificates', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Reverter mudanças
            $table->foreignId('quiz_id')->nullable(false)->change();
            $table->foreignId('quiz_attempt_id')->nullable(false)->change();
            
            // Verificar se a coluna score existe antes de tentar modificá-la
            if (Schema::hasColumn('certificates', 'score')) {
                $table->integer('score')->nullable(false)->change();
            }
            
            // Remover novos campos
            $table->dropColumn([
                'type',
                'title',
                'description',
                'category',
                'reference_id',
                'verification_code',
                'file_size'
            ]);
        });
    }
};