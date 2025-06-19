<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function send(Request $request)
    {
        $adminId = Auth::id();
        $deleted = false;

        $conversation = ChatConversation::firstOrCreate(
            ['admin_id' => $adminId],
            ['step' => 'start', 'data' => []]
        );

        $message = $request->input('message');
        $data = $conversation->data;

        switch ($conversation->step) {
            case 'start':
                $conversation->step = 'awaiting_name';
                $response = 'Hola, con gusto te apoyaré en la creación de un nuevo producto. ¿Cuál es el nombre del producto?';
                break;
            case 'awaiting_name':
                $data['name'] = $message;
                $conversation->step = 'awaiting_price';
                $response = '¿Cuál es el precio?';
                break;
            case 'awaiting_price':
                if (!is_numeric($message)) {
                    return response()->json(['reply' => 'Por favor, ingresa un número válido para el precio.']);
                }
                $data['price'] = (float) $message;
                $conversation->step = 'awaiting_cost';
                $response = '¿Cuál es el costo?';
                break;
            case 'awaiting_cost':
                if (!is_numeric($message)) {
                    return response()->json(['reply' => 'Por favor, ingresa un número válido para el costo.']);
                }

                $cost = (float) $message;
                if ($cost >= floatval($data['price'])) {
                    return response()-> json(['reply' => 'El costo no puede ser mayor o igual al precio.']);
                }

                $data['cost'] = (float) $message;
                $conversation->step = 'awaiting_stock';
                $response = '¿Cuántos hay en el inventario?';
                break;
            case 'awaiting_stock':
                if (!is_numeric($message)) {
                    return response()->json(['reply' => 'Por favor, ingresa un número válido de elementos en el almacén.']);
                }
                $data['stock'] = $message;
                $conversation->step = 'awaiting_brand';
                $response = '¿Cuál es la marca?';
                break;
            case 'awaiting_brand':
                $data['brand'] = $message;
                $conversation->step = 'awaiting_category_id';

                // Obtener lista de categorías
                $categories = Category::all();
                $options = $categories->map(fn ($c) => "{$c->id}: {$c->name}")->implode("<br>");

                $response = "Ingresa el número de la categoría a la que pertenece: <br>" . $options;
                break;
            
            case 'awaiting_category_id':
                $data['category_id'] = (int) $message;

                // Generar SKU
                $namePart = strtoupper(Str::slug(Str::limit($data['name'], 3, ''), ''));
                $brandPart = strtoupper(Str::slug(Str::limit($data['brand'], 3, ''), ''));
                $randomPart = strtoupper(Str::random(6));

                $sku = str_pad($namePart, 3, 'X') .
                    str_pad($brandPart, 3, 'X') .
                    $randomPart;

                $data['sku'] = $sku;

                // Obtener nombre de la categoría
                $category = Category::find($data['category_id']);
                $categoryName = $category?->name ?? 'Desconocida';

                $conversation->step = 'awaiting_confirmation';
                $response = "Por favor confirma los datos: <br>"
                    . "📝 <strong>Nombre</strong>: {$data['name']}<br>"
                    . "🏷️ <strong>Marca</strong>: {$data['brand']}<br>"
                    . "💲 <strong>Precio</strong>: {$data['price']}<br>"
                    . "💰 <strong>Costo</strong>: {$data['cost']}<br>"
                    . "📦 <strong>En almacén</strong>: {$data['stock']}<br>"
                    . "🔖 <strong>Categoría</strong>: {$categoryName}<br>"
                    . "📌 <strong>SKU generado</strong>: {$sku}<br>"
                    . "¿Deseas guardar este producto? (sí / no)";
                break;
            case 'awaiting_confirmation':
                if (strtolower($message) === 'sí' || strtolower($message) === 'si') {
                    $product = Product::create($data);
                    $conversation->delete();
                    $deleted = true;

                    $url = route('filament.admin.resources.products.edit', ['record' => $product->id]);
                    $button_style = 'border: #000 1px solid; padding: 2px 4px;';
                    $button = "<button style='$button_style'>Editar</button>";
                    $link = "<a href='$url' target='_blank'>$button</a>";

                    return response()->json([
                        'reply' => "✅ ¡Producto creado exitosamente!<br>📸 Aún falta agregar una foto.<br>Puedes editarlo aquí:<br>$link"
                    ]);
                } else {
                    $conversation->delete();
                    return response()->json(['reply' => '❌ Proceso cancelado. Puedes comenzar de nuevo cuando quieras.']);
                }
                break;
            default:
                $response = 'Algo salió mal. Empezando de nuevo...';
                $conversation->step = 'start';
                $data = [];
        }

        if (!$deleted) {
            $conversation->data = $data;
            $conversation->save();
        }

        return response()->json(['reply' => $response]);
    }
}