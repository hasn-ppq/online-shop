<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
    public function getFormattedPriceAttribute()
{
    return number_format($this->price, 2);
}

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    protected static function booted()
{
    static::saved(function () {
        Cache::flush();
    });

    static::deleted(function () {
        Cache::flush();
    });
}
}