<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\VehicleMaintenance;
use Illuminate\Support\Facades\DB;

class MaintenanceStatusDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Bakım Durumuna Göre Dağılım';

    protected function getData(): array
    {
        $rows = VehicleMaintenance::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $rows->pluck('total'),

                    // ✅ SUCCESS STİL (şeffaf iç + koyu border)
                    'backgroundColor' => $rows->map(fn () => 'rgba(34, 197, 94, 0.08)')->toArray(),
                    'borderColor'     => $rows->map(fn () => '#22c55e')->toArray(),
                    'borderWidth'     => 3,

                    // Hover
                    'hoverBackgroundColor' => $rows->map(fn () => 'rgba(34, 197, 94, 0.18)')->toArray(),
                    'hoverBorderColor'     => $rows->map(fn () => '#22c55e')->toArray(),
                ],
            ],
            'labels' => $rows->map(function ($row) {
                return match ($row->status) {
                    'planned'   => 'Planlandı',
                    'completed' => 'Yapıldı',
                    'cancelled' => 'İptal',
                    default     => $row->status,
                };
            }),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}