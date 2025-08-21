<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function chatbotFind(Request $request) {
        $search = $request->has('search') ? $request->input('search') : '';

        if (empty($search)) {
            return response()
                ->json([
                    'error' => 'Lo siento, no entendí, intenta otra vez por favor.'
                ], 400
            );
        }

        $products = Product::filterByName($search)
            ->activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->get();
        
        return response()->json(['products' => $products]);
    }
}
