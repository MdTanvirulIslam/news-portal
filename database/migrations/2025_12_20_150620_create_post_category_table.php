<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This creates the pivot table for many-to-many relationship
     */
    public function up(): void
    {
        // Check if table already exists
        if (!Schema::hasTable('post_category')) {
            Schema::create('post_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Prevent duplicate entries
                $table->unique(['category_id', 'post_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_category');
    }
};
