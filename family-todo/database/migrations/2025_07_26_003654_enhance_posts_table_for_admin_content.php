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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
            $table->enum('type', ['announcement', 'task_summary', 'general', 'important'])->default('general')->after('title');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->after('type');
            $table->boolean('is_pinned')->default(false)->after('status');
            $table->boolean('is_featured')->default(false)->after('is_pinned');
            $table->timestamp('published_at')->nullable()->after('is_featured');
            $table->timestamp('expires_at')->nullable()->after('published_at');
            $table->json('metadata')->nullable()->after('expires_at'); // For additional data like task lists, attachments, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'type',
                'status',
                'is_pinned',
                'is_featured',
                'published_at',
                'expires_at',
                'metadata'
            ]);
        });
    }
};
