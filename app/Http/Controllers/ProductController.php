<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(string $filter = '') {
        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->latest()
            ->paginate(20);

        return view('products.index', compact('products'));
    }

    public function show(string $sku = '') {

        if (empty($sku)) {
            return redirect()->route('home');
        }

        $product = Product::where('sku', $sku)->first();
        if (is_null($product)) {
            return redirect()->route('home');
        }

        return view('products.show', compact('product'));
    }
}
