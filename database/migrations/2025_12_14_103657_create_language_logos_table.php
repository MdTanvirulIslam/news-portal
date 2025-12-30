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
        Schema::create('language_logos', function (Blueprint $table) {
            $table->id();
            $table->string('english_logo')->nullable();
            $table->string('english_logo_alt')->default('English Logo');
            $table->string('bangla_logo')->nullable();
            $table->string('bangla_logo_alt')->default('বাংলা লোগো');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_logos');
    }
};
