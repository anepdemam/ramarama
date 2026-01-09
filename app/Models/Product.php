<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'stock_small',
        'stock_medium',
        'stock_large',
        'stock_xl',
        'stock_2xl',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
