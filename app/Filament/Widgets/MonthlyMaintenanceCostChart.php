<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\VehicleMaintenance;
use Illuminate\Support\Facades\DB;

class MonthlyMaintenanceCostChart extends ChartWidget
{
    protected static ?string $heading = 'Aylık Bakım Gideri';

    protected function getData(): array
    {
        $rows = VehicleMaintenance::query()
            ->select(
                DB::raw("DATE_FORMAT(maintenance_date, '%Y-%m') as month"),
                DB::raw('SUM(cost) as total_cost')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Toplam Tutar (₺)',
                    'data' => $rows->pluck('total_cost'),

                    // 🎨 MAVİ STİL
                    'borderColor'     => '#3b82f6',               // blue-500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.12)',
                    'borderWidth'     => 3,

                    'fill'            => true,
                    'tension'         => 0.4,

                    // Hover
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor'     => '#3b82f6',
                ],
            ],
            'labels' => $rows->pluck('month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}