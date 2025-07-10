<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Coupon;


class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->updateProductsStock();

        $sale = $this->record;

        $items = $sale->items;
        $subtotal = $items->sum(fn ($item) => $item->price * $item->quantity);
        $shipping = $sale->shipping_price ?? 0;
        $total = $subtotal;

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

        $sale->update([
            'subtotal' => $subtotal,
            'total' => $total + $shipping,
        ]);
    }

}
