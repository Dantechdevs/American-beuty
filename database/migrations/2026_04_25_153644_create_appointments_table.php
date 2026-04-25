<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Client info
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_email')->nullable();

            // Service
            $table->string('service_name');
            $table->string('service_category')->nullable();
            $table->decimal('service_price', 10, 2)->default(0);
            $table->integer('service_duration')->default(30); // in minutes

            // Appointment timing
            $table->date('appointment_date');
            $table->time('appointment_time');

            // Notes
            $table->text('notes')->nullable();

            // Status: pending | confirmed | cancelled | completed
            $table->string('status')->default('pending');

            // M-PESA deposit
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->string('mpesa_code')->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid | paid

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};