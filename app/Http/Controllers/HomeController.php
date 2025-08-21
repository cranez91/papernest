<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request) {

        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact('products'));
    }
}
