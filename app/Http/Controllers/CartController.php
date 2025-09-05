<?php
namespace App\Http\Controllers;

use App\Http\Resources\ShoppingCartItemResource;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getCart(Request $request)
    {
        $cart = ShoppingCart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'session_id' => auth()->check() ? null : $request->session()->getId(),
            ]
        );

        return $cart;
    }

    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        if ($cart) {
            $response = ShoppingCartItemResource::collection(
                $cart->items()->with('product')->get()
            );

            return response()->json(
                $response
            );
        }

        return response()->json([]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_sku' => 'required|exists:products,sku',
            'quantity'    => 'required|integer|min:1',
        ]);

        $cart = $this->getCart($request);

        $product = Product::where('sku', $request->product_sku)->first();

        // Validar stock
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'No hay suficientes en almacén'], 400);
        }

        $item = $cart->items()->updateOrCreate(
            ['product_id' => $product->id],
            ['quantity' => \DB::raw("quantity + {$request->quantity}")]
        );

        return response()->json($item->load('product'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = ShoppingCartItem::find($id);

        if (!$item) {
            return response()->json(['error' => 'Hubo un error, intentalo de nuevo'], 400);
        }

        // Validar stock
        if ($item->product->stock < $request->quantity) {
            return response()->json(['error' => 'No hay suficientes en almacén'], 400);
        }

        $item->update(['quantity' => $request->quantity]);

        return response()->json($item->load('product'));
    }

    public function destroy(int $id)
    {
        $item = ShoppingCartItem::find($id);

        if (!$item) {
            return response()->json(['error' => 'No se pudo eliminar el producto, intentalo de nuevo'], 400);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getCart($request);
        $cart->items()->delete();

        return response()->json(['success' => true]);
    }
}
