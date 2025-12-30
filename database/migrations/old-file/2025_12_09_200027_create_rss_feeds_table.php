<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rss_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('feed_url')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_import')->default(false);
            $table->integer('import_limit')->default(10); // Max items to import per fetch
            $table->timestamp('last_fetched_at')->nullable();
            $table->integer('total_imported')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rss_feeds');
    }
};
