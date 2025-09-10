<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, string $uuid)
    {
        $existing = Order::find($uuid);
        if ($existing) {
            return response()->json([
                'confirmation_url' => route('orders.confirmation', $existing->id),
            ], 200);
        }

        $data = $request->validated();

        $order = new Order();

        DB::transaction(function () use ($uuid, $data, $order) {
            $shopping_cart_id = $data['items'][0]['shopping_cart'];
            $address = $data['customer']['address'] . ', Col. ' . $data['customer']['neighborhood'] . ', ' . $data['customer']['city'] . ', Entre: ' . $data['customer']['streets'];

            $coupon = null;
            if (isset($data['customer']['coupon_code']) && !empty($data['customer']['coupon_code'])) {
                $coupon = Coupon::where('code', $data['customer']['coupon_code'])->first();
            }

            $order->id = $uuid;
            $order->customer_name = $data['customer']['name'];
            $order->email = $data['customer']['email'];
            $order->whatsapp = $data['customer']['whatsapp'];
            $order->address = $address;
            $order->status = 'pending';
            $order->source = 'web';
            $order->source_shopping_cart = $shopping_cart_id;

            if (!is_null($coupon)) {
                $order->coupon_id = $coupon->id;
            }

            $order->payment_type = 'cash_on_delivery';
            $order->subtotal = $data['subtotal'];
            $order->shipping_price = $data['shipping'];
            $order->total = $data['total'];
            $order->save();

            foreach ($data['items'] as $item) {
                $product = Product::where('sku', $item['product']['sku'])->first();

                if (!$product) {
                    throw new HttpResponseException(
                        response()->json([
                            'error' => 'Producto no encontrado'
                        ], 400)
                    );
                }

                $stock = $product->stock;
                $cart_count = ShoppingCartItem::where('product_id', $product->id)->sum('quantity');
                $available = $stock - $cart_count;

                if ($available <= 0) {
                    throw new HttpResponseException(
                        response()->json([
                            'error' => "No hay suficientes artículos en almacén: {$product->name}"
                        ], 422)
                    );
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

        });

        return response()->json([
            'confirmation_url' => route('orders.confirmation', $order->id),
        ], 201);
    }

    public function confirmation(string $orderId)
    {
        $order = Order::find($orderId);
    
        return Inertia::render('OrderConfirmation', [
            'order' => $order->load(['items.product', 'coupon']),
        ]);
    }

}
