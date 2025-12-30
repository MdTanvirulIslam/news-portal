<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update posts table - remove 'audio' from enum
        DB::statement("ALTER TABLE posts MODIFY COLUMN post_type ENUM('article', 'gallery', 'video') DEFAULT 'article'");

        // Update any existing 'audio' posts to 'article'
        DB::table('posts')->where('post_type', 'audio')->update(['post_type' => 'article']);

        // Add caption to post_media table (if not exists)
        if (Schema::hasTable('post_media') && !Schema::hasColumn('post_media', 'caption_en')) {
            Schema::table('post_media', function (Blueprint $table) {
                $table->text('caption_en')->nullable()->after('mime_type');
                $table->text('caption_bn')->nullable()->after('caption_en');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore 'audio' to enum
        DB::statement("ALTER TABLE posts MODIFY COLUMN post_type ENUM('article', 'gallery', 'video', 'audio') DEFAULT 'article'");

        // Remove caption columns
        if (Schema::hasTable('post_media')) {
            Schema::table('post_media', function (Blueprint $table) {
                $table->dropColumn(['caption_en', 'caption_bn']);
            });
        }
    }
};
