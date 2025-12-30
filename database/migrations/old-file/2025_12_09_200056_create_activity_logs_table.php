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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User who performed the action
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Action description
            $table->string('action');
            $table->text('description')->nullable();

            // Subject (what was acted upon)
            $table->string('subject_type')->nullable(); // Model class
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID

            // Additional data
            $table->json('properties')->nullable();

            // Request information
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
