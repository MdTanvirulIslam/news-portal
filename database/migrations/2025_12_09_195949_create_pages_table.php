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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Page titles (English & Bangla)
            $table->string('title_en');
            $table->string('title_bn');

            // Slugs for URLs (unique)
            $table->string('slug_en')->unique();
            $table->string('slug_bn')->unique();

            // Page content
            $table->longText('content_en');
            $table->longText('content_bn');

            // SEO fields
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_bn')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_bn')->nullable();

            // Status and ordering
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
