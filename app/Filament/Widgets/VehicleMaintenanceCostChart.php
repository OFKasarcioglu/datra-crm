<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\VehicleMaintenance;
use Illuminate\Support\Facades\DB;

class VehicleMaintenanceCostChart extends ChartWidget
{
    protected static ?string $heading = 'Araç Bazlı Toplam Bakım Maliyeti';

    protected function getData(): array
    {
        $rows = VehicleMaintenance::query()
            ->select('vehicles.plate', DB::raw('SUM(vehicle_maintenances.cost) as total_cost'))
            ->join('vehicles', 'vehicles.id', '=', 'vehicle_maintenances.vehicle_id')
            ->groupBy('vehicles.plate')
            ->orderByDesc('total_cost')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Toplam Tutar (₺)',
                    'data' => $rows->pluck('total_cost'),
                ],
            ],
            'labels' => $rows->pluck('plate'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}