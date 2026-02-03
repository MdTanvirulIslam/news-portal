<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lyricist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pen_name')->nullable();
            $table->json('writing_types')->nullable();
            $table->json('languages')->nullable();
            $table->json('portfolio_links')->nullable();
            $table->integer('songs_written')->default(0);
            $table->integer('years_of_experience')->default(0);
            $table->string('work_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('collaboration_availability')->nullable();
            $table->decimal('price_range_min', 10, 2)->nullable();
            $table->decimal('price_range_max', 10, 2)->nullable();
            $table->string('govt_id')->nullable();
            $table->string('copyright_declaration')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lyricist_profiles');
    }
};
