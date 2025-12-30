<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('subject_en');
            $table->string('subject_bn');
            $table->longText('content_en');
            $table->longText('content_bn');
            $table->foreignId('sent_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('total_subscribers')->default(0);
            $table->integer('successful_sends')->default(0);
            $table->integer('failed_sends')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
