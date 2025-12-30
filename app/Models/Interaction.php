<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $fillable = ['user_id', 'product_id', 'type', 'session_id', 'search_query', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Interaction types with weights for recommendation scoring
    const TYPE_VIEW = 'view';
    const TYPE_CLICK = 'click';
    const TYPE_CART = 'cart';
    const TYPE_PURCHASE = 'purchase';
    const TYPE_WISHLIST = 'wishlist';
    const TYPE_SEARCH = 'search';

    // Weights for scoring (higher = stronger preference signal)
    const WEIGHTS = [
        self::TYPE_VIEW => 1,
        self::TYPE_CLICK => 2,
        self::TYPE_WISHLIST => 3,
        self::TYPE_CART => 4,
        self::TYPE_PURCHASE => 5,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the weight score for this interaction type
     */
    public function getWeightAttribute(): int
    {
        return self::WEIGHTS[$this->type] ?? 0;
    }

    /**
     * Scope a query to only include interactions with weights
     */
    public function scopeWithWeights($query)
    {
        return $query->whereIn('type', array_keys(self::WEIGHTS));
    }

    /**
     * Create a purchase interaction for an order
     */
    public static function logPurchase($orderId)
    {
        $order = Order::with('items.product')->find($orderId);

        if (!$order) return;

        foreach ($order->items as $item) {
            self::create([
                'user_id' => $order->user_id,
                'product_id' => $item->product_id,
                'type' => self::TYPE_PURCHASE,
                'session_id' => null,
                'metadata' => [
                    'order_id' => $order->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ],
            ]);
        }
    }

    /**
     * Create a cart interaction
     */
    public static function logCart($userId, $productId)
    {
        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'type' => self::TYPE_CART,
            'session_id' => session()->getId(),
        ]);
    }

    /**
     * Create a wishlist interaction
     */
    public static function logWishlist($userId, $productId, $added = true)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'type' => self::TYPE_WISHLIST,
            ],
            [
                'session_id' => session()->getId(),
                'metadata' => ['added' => $added],
                'updated_at' => now(),
            ]
        );
    }
}
