<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request, string $categoria = '') {
        $sortByColumn = $request->filled('sortBy') ? $request->input('sortBy') : 'created_at';
        $sortByOrder = $request->filled('sortBy') ? $request->input('order') : 'desc';

        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->orderBy($sortByColumn, $sortByOrder)
            ->when($request->filled('search'), fn($q) => $q->filterByName($request->search))
            ->when($categoria, function($query) use ($categoria) {
                $query->whereHas('category', function($query) use ($categoria) {
                    $query->where('sku', $categoria);
                });
            })
            ->paginate(20);
        
        // Mantener los parámetros
        $products->appends($request->query());
        
        $categories = Category::orderBy('name', 'asc')
            ->get(['id', 'sku', 'name']);
        
        $selected_category = $categoria ? $categoria : null;

        return view('products.index', compact('products', 'categories', 'selected_category'));
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
