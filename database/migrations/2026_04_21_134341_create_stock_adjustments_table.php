
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();

            // What caused this movement
            $table->enum('type', [
                'purchase',      // from purchase module
                'pos_sale',      // from POS
                'online_sale',   // from online order
                'manual_add',    // admin added manually
                'manual_deduct', // admin deducted manually
                'damaged',       // marked as damaged
                'expired',       // marked as expired
            ]);

            $table->integer('quantity');          // always positive
            $table->enum('direction', ['in', 'out']); // in = stock added, out = stock removed
            $table->integer('stock_before');      // snapshot before change
            $table->integer('stock_after');       // snapshot after change
            $table->text('note')->nullable();     // optional reason/note

            // Optional references to source
            $table->nullableMorphs('reference');  // e.g. purchase_id, order_id

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_adjustments');
    }
};