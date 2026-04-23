<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();           // TXN-XXXXXX
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['payment','refund','wallet_topup','withdrawal']);
            $table->enum('method', ['mpesa','card','cash_on_delivery','wallet']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending','completed','failed','cancelled']);
            $table->string('provider_ref')->nullable();      // M-Pesa code, card txn ID etc.
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
