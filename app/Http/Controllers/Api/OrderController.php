<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, $uuid)
    {
        $existing = Order::find($uuid);
        if ($existing) {
            return response()->json($existing, 200);
        }

        $data = $request->validate([
            'customer_name' => 'required|string',
            'whatsapp' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($uuid, $data) {
            $order = Order::create([
                'id' => $uuid,
                'customer_name' => $data['customer_name'],
                'whatsapp' => $data['whatsapp'],
                'email' => $data['email'],
                'address' => $data['address'],
                'total' => $data['total'],
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        });

        return response()->json(Order::find($uuid), 201);
    }
}
