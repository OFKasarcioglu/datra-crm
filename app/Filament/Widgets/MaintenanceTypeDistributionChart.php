<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\VehicleMaintenance;
use Illuminate\Support\Facades\DB;

class MaintenanceTypeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Bakım Türüne Göre Dağılım';

   protected function getData(): array
{
    $rows = \App\Models\VehicleMaintenance::query()
        ->select('vehicles.plate', \Illuminate\Support\Facades\DB::raw('SUM(vehicle_maintenances.cost) as total_cost'))
        ->join('vehicles', 'vehicles.id', '=', 'vehicle_maintenances.vehicle_id')
        ->groupBy('vehicles.plate')
        ->orderByDesc('total_cost')
        ->get();

    return [
        'datasets' => [
            [
                'label' => 'Toplam Tutar (₺)',
                'data' => $rows->pluck('total_cost'),

                // 👇 STİL AYARLARI
             'backgroundColor'      => 'rgba(245, 158, 11, 0.08)',
'borderColor'          => '#f59e0b',
'borderWidth'          => 3,
'borderRadius'         => 6,
'hoverBackgroundColor' => 'rgba(245, 158, 11, 0.18)',
'hoverBorderColor'     => '#f59e0b',
            ],
        ],
        'labels' => $rows->pluck('plate'),
    ];
}

    protected function getType(): string
    {
        return 'pie';
    }
}