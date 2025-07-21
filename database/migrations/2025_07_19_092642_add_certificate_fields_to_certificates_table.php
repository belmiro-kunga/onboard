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
            $table->string('title')->after('certificate_number');
            $table->text('description')->after('title');
            $table->string('verification_code', 8)->unique()->after('valid_until');
            
            // Remover campo score se existir (será obtido da tentativa)
            if (Schema::hasColumn('certificates', 'score')) {
                $table->dropColumn('score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'verification_code']);
            $table->integer('score')->after('issued_at');
        });
    }
};