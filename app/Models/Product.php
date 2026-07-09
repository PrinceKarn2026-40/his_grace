<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'stock', 'image',
        'gender', 'featured', 'is_new', 'release_date',
    ];

    protected $casts = [
        'featured'     => 'boolean',
        'is_new'       => 'boolean',
        'price'        => 'decimal:2',
        'sale_price'   => 'decimal:2',
        'release_date' => 'date',
    ];

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('release_date')->orWhere('release_date', '<=', today());
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('release_date')->where('release_date', '>', today());
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->release_date && $this->release_date->isFuture();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
