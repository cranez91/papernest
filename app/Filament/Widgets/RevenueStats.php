<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Carbon\Carbon;

class RevenueStats extends BaseWidget
{
    protected function getStats(): array
    {
        $start = now('America/Mexico_City')->startOfDay()->timezone('UTC');
        $end = now('America/Mexico_City')->endOfDay()->timezone('UTC');

        $webRevenue = Order::where('status', 'paid')
            ->where('source', 'web')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');

        $saleRevenue = Order::where('status', 'paid')
            ->where('source', 'admin')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');

        return [
            Stat::make('Ingresos Ordenes Hoy', '$' . number_format($webRevenue, 2))
                ->description('Ventas realizadas desde la web')
                ->color('success'),

            Stat::make('Ingresos ventas Hoy', '$' . number_format($saleRevenue, 2))
                ->description('Ventas registradas en tienda')
                ->color('info'),
        ];
    }
}
