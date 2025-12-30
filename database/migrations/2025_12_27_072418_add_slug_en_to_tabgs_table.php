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
        Schema::table('tags', function (Blueprint $table) {
            // Add slug_en and slug_bn columns
            $table->string('slug_en')->nullable()->unique()->after('slug');
            $table->string('slug_bn')->nullable()->unique()->after('slug_en');
        });

        // Copy existing slug to both language columns
        DB::statement('UPDATE tags SET slug_en = slug, slug_bn = slug WHERE slug IS NOT NULL');

        // Now drop the old slug column
        Schema::table('tags', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });

        // Make the new slug columns NOT NULL
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug_en')->nullable(false)->change();
            $table->string('slug_bn')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            // Add back the old slug column
            $table->string('slug')->nullable()->unique()->after('name_bn');
        });

        // Copy slug_en to slug
        DB::statement('UPDATE tags SET slug = slug_en WHERE slug_en IS NOT NULL');

        // Drop the language-specific slug columns
        Schema::table('tags', function (Blueprint $table) {
            $table->dropUnique(['slug_en']);
            $table->dropUnique(['slug_bn']);
            $table->dropColumn(['slug_en', 'slug_bn']);
        });
    }
};
