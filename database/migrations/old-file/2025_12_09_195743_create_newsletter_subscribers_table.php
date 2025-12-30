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
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();

            // Subscriber information
            $table->string('email')->unique();
            $table->string('name')->nullable();

            // Status
            $table->enum('status', ['active', 'unsubscribed', 'bounced'])->default('active');

            // Subscription details
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();

            // Verification
            $table->string('token')->unique()->nullable();
            $table->timestamp('verified_at')->nullable();

            // Tracking
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
