<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, string $category = '') {
        $sortByColumn = $request->filled('sortBy') ? $request->input('sortBy') : 'created_at';
        $sortByOrder = $request->filled('sortBy') ? $request->input('order') : 'desc';

        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->orderBy($sortByColumn, $sortByOrder)
            ->when($request->filled('search'), fn($q) => $q->filterByNameOrTags($request->search))
            ->when($category, function($query) use ($category) {
                $query->whereHas('category', function($query) use ($category) {
                    $query->where('sku', $category);
                });
            })
            ->paginate(20)
            ->withQueryString();
        
        $categories = Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get(['id', 'sku', 'name']);

        $selected_category = $category ? $category : '';

        return Inertia::render('Products', [
            'products' => $products->toArray(),
            'categories' => $categories,
            'selectedCategory' => $selected_category,
        ]);
    }

    public function show(string $sku = '') {

        if (empty($sku)) {
            return redirect()->route('home');
        }

        $product = Product::where('sku', $sku)
            ->with('tags:name')
            ->first();

        if (is_null($product)) {
            return redirect()->route('home');
        }

        return Inertia::render('ProductDetail', [
            'product' => $product
        ]);
    }
}
