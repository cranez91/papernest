<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Order;

class MonthlyOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos Ordenes en Línea';
    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Trend::query(
            Order::query()
                ->where('status', 'paid')
                ->where('source', 'web')
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
                    'label' => 'Ingresos Ordenes en Línea',
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
