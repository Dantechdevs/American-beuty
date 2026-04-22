<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('employee_id')
                  ->references('id')->on('employees')
                  ->cascadeOnDelete();

            $table->foreign('shift_id')
                  ->references('id')->on('shifts')
                  ->nullOnDelete();

            $table->foreign('overridden_by')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['shift_id']);
            $table->dropForeign(['overridden_by']);
        });
    }
};
