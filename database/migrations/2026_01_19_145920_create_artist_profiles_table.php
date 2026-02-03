<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('stage_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('nationality')->nullable();
            $table->enum('artist_type', ['singer', 'performer', 'band_member', 'rapper', 'instrumentalist'])->nullable();

            // Professional Profile
            $table->json('genres')->nullable(); // ['pop', 'rock', 'classical']
            $table->json('languages')->nullable(); // ['english', 'bangla', 'hindi']
            $table->integer('years_of_experience')->nullable();
            $table->string('vocal_type')->nullable();
            $table->json('instruments')->nullable(); // ['guitar', 'piano']

            // Music Portfolio
            $table->json('portfolio_links')->nullable(); // YouTube, Spotify, SoundCloud links
            $table->string('demo_audio')->nullable(); // File path
            $table->json('previous_albums')->nullable();
            $table->json('performance_videos')->nullable();

            // Business Information
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('booking_email')->nullable();
            $table->decimal('live_show_price_min', 10, 2)->nullable();
            $table->decimal('live_show_price_max', 10, 2)->nullable();
            $table->decimal('studio_recording_fee', 10, 2)->nullable();
            $table->json('location_availability')->nullable(); // ['local', 'national', 'international']

            // Verification Documents
            $table->string('govt_id')->nullable(); // File path
            $table->string('artist_contract')->nullable(); // File path
            $table->string('copyright_declaration')->nullable(); // File path
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artist_profiles');
    }
};
