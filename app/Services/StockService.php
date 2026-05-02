<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockAlert;
use App\Models\User;
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
        $adjustment = StockAdjustment::create([
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

        // Check low stock alert after every 'out' movement
        if ($direction === 'out') {
            self::checkAndSendLowStockAlert($product->fresh(), $stockAfter);
        }

        return $adjustment;
    }

    /**
     * Check if product has hit low stock threshold and SMS admin if so.
     */
    private static function checkAndSendLowStockAlert(Product $product, int $stockAfter): void
    {
        $alert = StockAlert::where('product_id', $product->id)
                    ->where('is_active', true)
                    ->first();

        // No alert configured for this product
        if (!$alert) return;

        // Stock is still above threshold — nothing to do
        if ($stockAfter > $alert->low_stock_threshold) return;

        // Avoid spamming — only send if not notified in the last 24 hours
        if ($alert->notified_at && $alert->notified_at->diffInHours(now()) < 24) return;

        // Build SMS message
        $level   = $stockAfter <= 0 ? 'OUT OF STOCK' : 'LOW STOCK';
        $message = "⚠️ {$level} ALERT — American Beauty\n"
            . "Product: {$product->name}\n"
            . "SKU: {$product->sku}\n"
            . "Current Stock: {$stockAfter} units\n"
            . "Threshold: {$alert->low_stock_threshold} units\n"
            . "Please restock soon.";

        // Send SMS to all active admins and managers with a phone number
        $admins = User::whereIn('role', ['admin', 'manager'])
                    ->where('is_active', true)
                    ->whereNotNull('phone')
                    ->get();

        $notificationService = app(NotificationService::class);

        foreach ($admins as $admin) {
            $notificationService->sendRawSms($admin->phone, $message);
        }

        // Mark alert as notified so we don't spam
        $alert->markNotified();
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