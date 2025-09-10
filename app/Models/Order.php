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
        'source_shopping_cart',
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
        $shipping = $this->shipping_price ?? 0;
        $subtotal = $this->getSubtotal();
        $total = $subtotal;

        $coupon = $this->coupon;
        if ($coupon) {
            $now = now('America/Mexico_City')->timezone('UTC');

            if ($coupon->status === 'active' &&
                $now->between($coupon->start_date, $coupon->end_date) &&
                $subtotal >= $coupon->min_total
            ) {
                $discount_amount = ($subtotal * $coupon->discount_percentage) / 100;
                $total -= $discount_amount;
            }
        }

        $total += $shipping;

        $this->subtotal = $subtotal;
        $this->total = $total;
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

    private function getSubtotal(): int
    {
        return $this->items()
            ->sum(DB::raw('quantity * price'));
    }
}
