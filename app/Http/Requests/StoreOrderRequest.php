<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // --- Customer ---
            'customer.name' => ['required', 'string', 'min:5', 'max:100'],
            'customer.email' => ['nullable', 'email', 'max:100'], // opcional
            'customer.whatsapp' => ['required', 'string', 'min:10', 'max:10'],
            'customer.address' => ['required', 'string', 'max:50'],
            'customer.neighborhood' => ['required', 'string', 'max:50'],
            'customer.streets' => ['required', 'string', 'max:80'],
            'customer.city' => ['required', 'string', 'max:80'],
            'customer.coupon_code' => ['nullable', 'string', 'exists:coupons,code'],

            // --- Items ---
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:shopping_cart_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.shopping_cart' => ['required', 'string', 'exists:shopping_carts,id'],
            
            'items.*.product' => ['required'],
            'items.*.product.sku' => ['required', 'string', 'exists:products,sku'],
            'items.*.product.name' => ['required', 'string', 'max:100'],
            'items.*.product.price' => ['required', 'numeric', 'min:1'],

            // --- Totales ---
            'subtotal' => ['required', 'numeric', 'min:1'],
            'shipping' => ['required', 'numeric', 'min:1'],
            'total' => ['required', 'numeric', 'min:1'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors()
            ], 422)
        );
    }

    public function attributes(): array
    {
        return [
            'customer.name' => 'customer name',
            'customer.whatsapp' => 'WhatsApp',
            'items.*.quantity' => 'item quantity',
        ];
    }

    public function messages(): array
    {
        return [
            // Customer
            'customer.name.required' => 'El nombre es un valor obligatorio.',
            'customer.name.min' => 'El nombre debe contener almenos 5 letras.',
            'customer.email.email' => 'Por favor, infresa un correo electrónico válido.',
            'customer.whatsapp.required' => 'El WhatsApp es un valor obligatorio.',
            'customer.whatsapp.min' => 'El WhatsApp debe contener almenos 10 dígitos.',
            'customer.whatsapp.max' => 'El WhatsApp no debe contener más de 10 dígitos.',
            'customer.address.required' => 'El domicilio es un valor obligatorio.',
            'customer.city.required' => 'El municipio es un valor obligatorio.',
            'customer.neighborhood.required' => 'El municipio es un valor obligatorio.',
            'customer.streets.required' => 'Entre calles es un valor obligatorio.',
            'customer.coupon_code.exists' => 'El cupón utilizado no existe o no está disponible.',

            // Items
            'items.required' => 'Tu orden debe contener almenos un producto.',
            'items.*.id.required' => 'Cada articulo debe ser agregado al carrito.',
            'items.*.id.exists' => 'Uno de los productos agregados no existe más.',
            'items.*.product.sku.string' => 'El sku del producto debe ser texto.',
            'items.*.quantity.required' => 'Cada artículo debe tener definida la cantidad.',
            'items.*.quantity.min' => 'La cantidad mínima por artículo deber ser 1.',

            // Totals
            'subtotal.required' => 'Subtotal es un valor obligatorio.',
            'shipping.required' => 'El envío es un valor obligatorio.',
            'total.required' => 'Total es un valor obligatorio.',
        ];
    }

}
