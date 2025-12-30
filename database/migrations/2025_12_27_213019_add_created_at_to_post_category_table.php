<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds created_at and updated_at columns to post_category pivot table
     */
    public function up(): void
    {
        Schema::table('post_category', function (Blueprint $table) {
            // Add timestamps if they don't exist
            if (!Schema::hasColumn('post_category', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_category', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
