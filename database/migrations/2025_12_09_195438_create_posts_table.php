<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Post type
            $table->enum('post_type', ['article', 'gallery', 'video', 'audio'])->default('article');
            
            // Bilingual titles and slugs
            $table->string('title_en')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('slug_en')->nullable()->unique();
            $table->string('slug_bn')->nullable()->unique();
            
            // Bilingual content
            $table->longText('content_en')->nullable();
            $table->longText('content_bn')->nullable();
            
            // Bilingual excerpts
            $table->text('excerpt_en')->nullable();
            $table->text('excerpt_bn')->nullable();
            
            // Media files
            $table->string('featured_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_file')->nullable();
            
            // SEO - Bilingual
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_bn')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_bn')->nullable();
            $table->text('meta_keywords_en')->nullable();
            $table->text('meta_keywords_bn')->nullable();
            
            // Status and flags
            $table->enum('status', ['draft', 'pending', 'published', 'rejected', 'scheduled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_breaking')->default(false);
            $table->boolean('allow_comments')->default(true);
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Counters
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            
            // Review tracking
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reject_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('post_type');
            $table->index('is_featured');
            $table->index('is_breaking');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
