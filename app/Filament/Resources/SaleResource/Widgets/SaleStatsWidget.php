<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;

class SaleStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $start = now('America/Mexico_City')->startOfDay()->timezone('UTC');
        $end = now('America/Mexico_City')->endOfDay()->timezone('UTC');

        $saleRevenue = Order::where('status', 'paid')
            ->where('source', 'admin')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');

        return [
            Stat::make('Ingresos Hoy', '$' . number_format($saleRevenue, 2))
                ->description('Ventas registradas en tienda')
                ->color('info'),
        ];
    }
}
