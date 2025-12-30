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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // This creates BIGINT UNSIGNED

            // Parent category (nullable for root categories)
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');

            // Category names (English & Bangla)
            $table->string('name_en');
            $table->string('name_bn');

            // Slugs for URLs (unique)
            $table->string('slug_en')->unique();
            $table->string('slug_bn')->unique();

            // Descriptions
            $table->text('description_en')->nullable();
            $table->text('description_bn')->nullable();

            // Category image
            $table->string('image')->nullable();

            // Display order
            $table->integer('order')->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_menu')->default(true);

            // SEO fields
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_bn')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_bn')->nullable();
            $table->text('meta_keywords_en')->nullable();
            $table->text('meta_keywords_bn')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Add soft deletes

            // Indexes
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
