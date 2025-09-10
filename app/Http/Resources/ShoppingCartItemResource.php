<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $category = $product->category;

        return [
            'id' => $this->id,
            'shopping_cart' => $this->shopping_cart_id,
            'quantity' => $this->quantity,
            'product' => [
                'sku' => $product->sku,
                'name' => $product->name,
                'slug' => $product->slug,
                'photo' => $product->photo,
                'brand' => $product->brand,
                'price' => $product->price,
                'stock' => $product->stock,
                'short_name' => $product->short_name,
                'category_name' => $category->name,
                'category_sku' => $category->sku,
                'description' => $product->description,
            ],
        ];
        
    }
}
