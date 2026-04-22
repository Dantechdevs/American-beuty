<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('pin', 10)->nullable();           // clock-in PIN
            $table->string('role')->default('cashier');      // cashier|beautician|receptionist|manager|cleaner
            $table->string('photo')->nullable();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->date('joined_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};