<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');      // FK added manually after
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->date('date');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->integer('hours_worked')->nullable();
            $table->enum('status', [
                'present','late','early_out','absent','half_day',
            ])->default('present');
            $table->text('note')->nullable();
            $table->boolean('admin_override')->default(false);
            $table->unsignedBigInteger('overridden_by')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
