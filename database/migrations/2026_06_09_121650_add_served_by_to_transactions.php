<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('served_by')->nullable()->constrained('employees')->nullOnDelete()->after('created_by');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('served_by')->nullable()->constrained('employees')->nullOnDelete()->after('id');
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('served_by')->nullable()->constrained('employees')->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices',     function (Blueprint $table) { $table->dropForeign(['served_by']); $table->dropColumn('served_by'); });
        Schema::table('purchases',    function (Blueprint $table) { $table->dropForeign(['served_by']); $table->dropColumn('served_by'); });
        Schema::table('appointments', function (Blueprint $table) { $table->dropForeign(['served_by']); $table->dropColumn('served_by'); });
    }
};
