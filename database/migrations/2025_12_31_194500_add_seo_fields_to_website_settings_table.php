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
        Schema::table('website_settings', function (Blueprint $table) {
            // Add google site verification code
            if (!Schema::hasColumn('website_settings', 'google_verification')) {
                $table->string('google_verification')->nullable()->after('rss_url');
            }

            // Add OG image for social sharing
            if (!Schema::hasColumn('website_settings', 'og_image')) {
                $table->string('og_image')->nullable()->after('google_verification');
            }

            // Add meta title if not exists
            if (!Schema::hasColumn('website_settings', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('website_title');
            }

            // Add meta description if not exists
            if (!Schema::hasColumn('website_settings', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }

            // Add meta keywords if not exists
            if (!Schema::hasColumn('website_settings', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_verification',
                'og_image',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};
