<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->integer('hours_worked')->nullable();   // in minutes
            $table->enum('status', [
                'present',
                'late',
                'early_out',
                'absent',
                'half_day',
            ])->default('present');
            $table->text('note')->nullable();              // admin note / override reason
            $table->boolean('admin_override')->default(false);
            $table->foreignId('overridden_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // One record per employee per day
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};