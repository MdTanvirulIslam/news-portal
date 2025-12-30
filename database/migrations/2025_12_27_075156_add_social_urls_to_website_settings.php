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
            // Add social media URL fields
            $table->string('facebook_url')->nullable()->after('facebook_pixel');
            $table->string('twitter_url')->nullable()->after('facebook_url');
            $table->string('linkedin_url')->nullable()->after('twitter_url');
            $table->string('youtube_url')->nullable()->after('linkedin_url');
            $table->string('whatsapp_url')->nullable()->after('youtube_url');
            $table->string('instagram_url')->nullable()->after('whatsapp_url');
            $table->string('rss_url')->nullable()->after('instagram_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_url',
                'twitter_url',
                'linkedin_url',
                'youtube_url',
                'whatsapp_url',
                'instagram_url',
                'rss_url',
            ]);
        });
    }
};
