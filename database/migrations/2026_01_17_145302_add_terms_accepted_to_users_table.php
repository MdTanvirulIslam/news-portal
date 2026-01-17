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
        Schema::table('users', function (Blueprint $table) {
            // Common Registration Fields (Phase 1)
            $table->string('country')->nullable()->after('phone');
            $table->string('city')->nullable()->after('country');

            $table->boolean('terms_accepted')->default(false)->after('is_active');
            $table->boolean('copyright_accepted')->default(false)->after('terms_accepted');
            $table->timestamp('terms_accepted_at')->nullable()->after('copyright_accepted');

            // Profile Completion Status
            $table->boolean('profile_completed')->default(false)->after('terms_accepted_at');
            $table->timestamp('profile_completed_at')->nullable()->after('profile_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'city',
                'terms_accepted',
                'copyright_accepted',
                'terms_accepted_at',
                'profile_completed',
                'profile_completed_at'
            ]);
        });
    }
};
