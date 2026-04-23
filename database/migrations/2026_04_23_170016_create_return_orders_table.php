<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // customer
            $table->enum('status', [
                'pending', 'reviewing', 'approved', 'rejected', 'refunded', 'closed'
            ])->default('pending');
            $table->enum('initiated_by', ['customer', 'admin'])->default('customer');
            $table->integer('quantity');
            $table->string('reason');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('refund_method', ['wallet', 'original_payment', 'store_credit', 'cash'])->nullable();
            $table->boolean('stock_restored')->default(false);
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_orders');
    }
};