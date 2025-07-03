<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public $incrementing = false; // 👈 importante para UUID
    protected $keyType = 'string';

    protected $fillable = [
        'customer_name',
        'whatsapp',
        'email',
        'address',
        'status',
        'source',
        'payment_type',
        'subtotal',
        'shipping_price',
        'total',
        'coupon_id',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->id)) {
                $order->id = (string) Str::uuid();
            }
            if (empty($order->customer_name) && empty($order->whatsapp)) {
                $order->status = 'paid';
                $order->source = 'admin';
                $order->update();
            }
        });

        static::saved(function ($order) {
            $order->updateTotalPrice();
        });
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateTotalPrice()
    {
        $this->subtotal = $this->items()->sum(DB::raw('quantity * price'));
        $this->total = $this->subtotal + $this->shipping_price;
        $this->saveQuietly();
    }

    public function updateProductsStock() 
    {
        $order_items = $this->items()->with('product')->get();
        foreach($order_items as $item) {
            $product = $item->product;
            $product->stock = max(0, $product->stock - $item->quantity);
            $product->save();
        }
    }
}
