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
        Schema::create('logo_settings', function (Blueprint $table) {
            $table->id();
            
            // Logo Images
            $table->string('main_logo')->nullable()->comment('Main website logo (header)');
            $table->string('footer_logo')->nullable()->comment('Footer logo');
            $table->string('favicon')->nullable()->comment('Browser favicon (16x16 or 32x32)');
            $table->string('lazy_banner')->nullable()->comment('Lazy loading placeholder image');
            $table->string('og_image')->nullable()->comment('Open Graph / Social media share image');
            
            // Logo Alt Texts
            $table->string('main_logo_alt')->nullable()->default('Website Logo');
            $table->string('footer_logo_alt')->nullable()->default('Footer Logo');
            
            // Logo Dimensions (optional, for validation)
            $table->string('main_logo_width')->nullable();
            $table->string('main_logo_height')->nullable();
            
            $table->timestamps();
        });

        // Insert default record
        DB::table('logo_settings')->insert([
            'main_logo_alt' => 'Website Logo',
            'footer_logo_alt' => 'Footer Logo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_settings');
    }
};
