<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $start = now('America/Mexico_City')->startOfDay()->timezone('UTC');
        $end = now('America/Mexico_City')->endOfDay()->timezone('UTC');

        $webRevenue = Order::where('status', 'paid')
            ->where('source', 'web')
            ->whereBetween('created_at', [$start, $end])
            ->sum('subtotal');

        return [
            Stat::make('Ingresos Hoy', '$' . number_format($webRevenue, 2))
                ->description('Ventas realizadas desde la web')
                ->color('success'),
        ];
    }
}
