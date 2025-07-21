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
            if (!Schema::hasColumn('certificates', 'reference_type')) {
                $table->string('reference_type')->nullable()->after('reference_id'); // simulado, module, quiz, etc.
            }
            if (!Schema::hasColumn('certificates', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('issued_at');
            }
            if (!Schema::hasColumn('certificates', 'revoked_at')) {
                $table->timestamp('revoked_at')->nullable()->after('expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'reference_type',
                'expires_at',
                'revoked_at'
            ]);
        });
    }
};
