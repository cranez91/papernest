<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request) {
        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->latest()
            ->take(4)
            ->get();

        return Inertia::render('Home', [
            'products' => $products
        ]);
    }

    public function cart(Request $request) {
        $shipping_price = Setting::first()?->shipping_price ?? 0;

        return Inertia::render('Cart', [
            'shipping' => $shipping_price
        ]);
    }
}
