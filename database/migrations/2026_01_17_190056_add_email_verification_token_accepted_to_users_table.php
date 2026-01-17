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
            // Add email verification token and timestamp if not exists
            if (!Schema::hasColumn('users', 'email_verification_token')) {
                $table->string('email_verification_token')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'email_verification_sent_at')) {
                $table->timestamp('email_verification_sent_at')->nullable()->after('email_verification_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verification_token', 'email_verification_sent_at']);
        });
    }
};
