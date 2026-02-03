<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('composer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('composer_types')->nullable();
            $table->json('genres')->nullable();
            $table->json('instruments_knowledge')->nullable();
            $table->boolean('studio_availability')->default(false);
            $table->json('sample_works')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('work_charges_min', 10, 2)->nullable();
            $table->decimal('work_charges_max', 10, 2)->nullable();
            $table->text('recording_location')->nullable();
            $table->string('booking_email')->nullable();
            $table->string('booking_phone')->nullable();
            $table->string('govt_id')->nullable();
            $table->string('previous_work_docs')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('composer_profiles');
    }
};
