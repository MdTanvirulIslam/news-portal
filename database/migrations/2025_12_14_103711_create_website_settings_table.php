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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_title')->nullable();
            $table->boolean('show_loader')->default(true);
            $table->string('loader_type')->default('spinner');
            $table->string('loader_image')->nullable();
            $table->string('base_color', 7)->default('#667eea');
            $table->string('footer_color', 7)->default('#2d3748');
            $table->string('copyright_color', 7)->default('#1a202c');
            $table->string('header_text_color', 7)->default('#ffffff');
            $table->string('link_color', 7)->default('#667eea');
            $table->string('link_hover_color', 7)->default('#764ba2');
            $table->string('heading_font', 100)->default('Poppins');
            $table->string('body_font', 100)->default('Roboto');
            $table->string('timezone', 100)->default('UTC');
            $table->integer('posts_per_page')->default(10);
            $table->string('google_search_console', 500)->nullable();
            $table->string('google_adsense', 500)->nullable();
            $table->string('google_analytics', 500)->nullable();
            $table->string('facebook_pixel', 500)->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
