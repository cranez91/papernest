<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Order;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas registradas en tienda';
    protected static string $color = 'info';

    protected function getData(): array
    {
        $data = Trend::query(
            Order::query()
                ->where('status', 'paid')
                ->where('source', 'admin')
            )
            ->between(
                start: now()->startOfYear()->timezone('UTC'),
                end: now()->endOfYear()->timezone('UTC'),
            )
            ->perMonth()
            ->sum('total');    

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos por ventas',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
