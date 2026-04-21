<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // e.g. Morning, Evening, Full Day
            $table->time('start_time');                  // e.g. 08:00
            $table->time('end_time');                    // e.g. 16:00
            $table->integer('grace_minutes')->default(15); // late tolerance in minutes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shifts');
    }
};