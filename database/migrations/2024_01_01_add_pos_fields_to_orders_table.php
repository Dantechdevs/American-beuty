<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add only if columns don't already exist
            if (!Schema::hasColumn('orders', 'source')) {
                $table->enum('source', ['online', 'pos'])->default('online')->after('status');
            }
            if (!Schema::hasColumn('orders', 'served_by')) {
                $table->unsignedBigInteger('served_by')->nullable()->after('source');
                $table->foreign('served_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 12, 2)->default(0)->after('subtotal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['source', 'served_by', 'subtotal', 'discount']);
        });
    }
};
