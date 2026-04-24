<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'sms_sent')) {
                $table->boolean('sms_sent')->default(false)->after('is_read');
            }
            if (!Schema::hasColumn('notifications', 'sms_sid')) {
                $table->string('sms_sid')->nullable()->after('sms_sent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'sms_sid')) {
                $table->dropColumn('sms_sid');
            }
            if (Schema::hasColumn('notifications', 'sms_sent')) {
                $table->dropColumn('sms_sent');
            }
        });
    }
};