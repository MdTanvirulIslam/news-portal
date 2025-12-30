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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Key-value pair
            $table->string('key')->unique();
            $table->text('value')->nullable();

            // Grouping
            $table->string('group')->default('general'); // general, logo, seo, social, smtp

            // Data type hint
            $table->enum('type', ['text', 'textarea', 'number', 'boolean', 'file', 'image', 'email', 'url', 'color'])
                ->default('text');

            // Description
            $table->string('description')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('key');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
