<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;


class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        $this->record->updateProductsStock();

        $sale = $this->record; // Ya creado

        // Asegúrate de que los items estén cargados
        $items = $sale->items;
        

        $subtotal = $items->sum(fn ($item) => $item->price * $item->quantity);
        $shipping = $sale->shipping_price ?? 0;
        $total = $subtotal;

        // Verifica y aplica el cupón si es válido
        if ($sale->coupon_id) {
            $coupon = Coupon::find($sale->coupon_id);
            $now = now('America/Mexico_City')->timezone('UTC');

            if (
                $coupon &&
                $coupon->status === 'active' &&
                $now->between($coupon->start_date, $coupon->end_date) &&
                $subtotal >= $coupon->min_total
            ) {
                $discount = ($subtotal * $coupon->discount_percentage) / 100;
                $total -= $discount;
            }
        }

        // Guardar los totales
        $sale->update([
            'subtotal' => $subtotal,
            'total' => $total + $shipping,
        ]);

        Log::info('dadada', [
            'subtotal' => $subtotal,
            'total' => $total + $shipping,
        ]);
    }


    /*protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = collect($data['items'] ?? []);

        $subtotal = $items->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $total = $subtotal;

        $shipping = $data['shipping_price'] ?? 0;

        if (!empty($data['coupon_id'])) {
            $coupon = Coupon::find($data['coupon_id']);
            $now = now();

            if (
                $coupon &&
                $coupon->status === 'active' &&
                $now->between($coupon->start_date, $coupon->end_date) &&
                $subtotal >= $coupon->min_total
            ) {
                $discount = ($subtotal * $coupon->discount_percentage) / 100;
                $total -= $discount;
            }
        }

        $data['subtotal'] = $subtotal;
        $data['total'] = $total + $shipping;
        Log::info('dasda', $data);
        dd('detente!');
        
        return $data;
    }*/
}
