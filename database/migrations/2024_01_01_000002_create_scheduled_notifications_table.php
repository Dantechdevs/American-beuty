<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('type')->default('custom');
            $table->string('icon')->default('fas fa-bell');
            $table->string('url')->nullable();
            $table->enum('audience', ['all', 'customers', 'admins', 'managers', 'pos_operators', 'specific']);
            $table->unsignedBigInteger('specific_user_id')->nullable();
            $table->boolean('send_sms')->default(false);
            $table->timestamp('scheduled_at');
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_notifications');
    }
};
