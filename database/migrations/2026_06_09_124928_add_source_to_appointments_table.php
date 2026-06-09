<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('source', ['online', 'walkin', 'manual'])->default('online')->after('id');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('service_price');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['source', 'amount_paid']);
        });
    }
};
