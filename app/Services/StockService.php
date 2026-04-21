<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Record any stock movement.
     * Call this from PurchaseController, PosController, OrderController, etc.
     */
    public static function adjust(
        int    $productId,
        string $type,
        string $direction,   // 'in' or 'out'
        int    $quantity,
        string $note = null,
        mixed  $reference = null, // model instance e.g. $purchase
        int    $userId = null
    ): StockAdjustment {

        $product = Product::findOrFail($productId);

        $stockBefore = $product->stock_quantity;
        $stockAfter  = $direction === 'in'
            ? $stockBefore + $quantity
            : max(0, $stockBefore - $quantity);

        // Update product stock
        $product->update(['stock_quantity' => $stockAfter]);

        // Log the adjustment
        return StockAdjustment::create([
            'product_id'     => $productId,
            'created_by'     => $userId ?? Auth::id(),
            'type'           => $type,
            'direction'      => $direction,
            'quantity'       => $quantity,
            'stock_before'   => $stockBefore,
            'stock_after'    => $stockAfter,
            'note'           => $note,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
        ]);
    }

    /**
     * Shorthand helpers
     */
    public static function addFromPurchase(int $productId, int $qty, $purchase): StockAdjustment
    {
        return self::adjust($productId, 'purchase', 'in', $qty, 'Stock added from purchase '.$purchase->invoice_no, $purchase);
    }

    public static function deductFromPos(int $productId, int $qty, $order): StockAdjustment
    {
        return self::adjust($productId, 'pos_sale', 'out', $qty, 'Sold via POS', $order);
    }

    public static function deductFromOnlineOrder(int $productId, int $qty, $order): StockAdjustment
    {
        return self::adjust($productId, 'online_sale', 'out', $qty, 'Sold via online order #'.$order->order_number, $order);
    }

    public static function markDamaged(int $productId, int $qty, string $note = null): StockAdjustment
    {
        return self::adjust($productId, 'damaged', 'out', $qty, $note ?? 'Marked as damaged');
    }

    public static function markExpired(int $productId, int $qty, string $note = null): StockAdjustment
    {
        return self::adjust($productId, 'expired', 'out', $qty, $note ?? 'Marked as expired');
    }
}