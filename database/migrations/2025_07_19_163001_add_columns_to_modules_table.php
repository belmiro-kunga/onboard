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
            $table->string('title')->after('id');
            $table->text('description')->after('title');
            $table->enum('category', ['hr', 'it', 'security', 'processes', 'culture', 'compliance', 'produtos'])->after('description');
            $table->integer('order_index')->default(0)->after('category');
            $table->boolean('is_active')->default(true)->after('order_index');
            $table->integer('points_reward')->default(0)->after('is_active');
            $table->integer('estimated_duration')->nullable()->after('points_reward'); // em minutos
            $table->enum('content_type', ['video', 'text', 'interactive', 'document'])->default('text')->after('estimated_duration');
            $table->json('content_data')->nullable()->after('content_type');
            $table->string('thumbnail')->nullable()->after('content_data');
            $table->enum('difficulty_level', ['basic', 'intermediate', 'advanced'])->default('basic')->after('thumbnail');
            $table->json('prerequisites')->nullable()->after('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'category',
                'order_index',
                'is_active',
                'points_reward',
                'estimated_duration',
                'content_type',
                'content_data',
                'thumbnail',
                'difficulty_level',
                'prerequisites'
            ]);
        });
    }
};
