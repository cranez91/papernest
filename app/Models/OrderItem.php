<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if (is_null($item->price)) {
                $item->price = $item->product?->price ?? 0;
                
            }
            if (is_null($item->cost)) {
                $item->cost = $item->product?->cost ?? 0;
                
            }
        });

        /*static::saved(function ($item) {
            $item->order->updateTotalPrice();
        });

        static::deleted(function ($item) {
            $item->order->updateTotalPrice();
        });*/
    }
}
