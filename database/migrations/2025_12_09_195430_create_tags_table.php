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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->unique();
            $table->string('name_bn')->unique();
            $table->string('slug')->unique();
            $table->text('description_en')->nullable();
            $table->text('description_bn')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_bn')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_bn')->nullable();
            $table->string('meta_keywords_en')->nullable();
            $table->string('meta_keywords_bn')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for posts and tags
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('tags');
    }
};
