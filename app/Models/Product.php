<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'condition',
        'size',
        'brand',
        'color',
        'images',
        'is_available',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include available products (not sold)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include sold products
     */
    public function scopeSold($query)
    {
        return $query->where('is_available', false);
    }
}
