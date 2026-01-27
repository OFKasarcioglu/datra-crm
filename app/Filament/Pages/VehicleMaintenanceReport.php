<?php

namespace App\Filament\Pages;

use App\Models\VehicleMaintenance;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class VehicleMaintenanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Araç Raporları';
    protected static ?string $navigationGroup = 'Araç Yönetimi';
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar-square';
    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Araç Bakım Raporları';

    protected static string $view = 'filament.pages.vehicle-maintenance-report';

    /**
     * Üst kısımdaki analiz chartları
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\VehicleMaintenanceCostChart::class,
            \App\Filament\Widgets\MonthlyMaintenanceCostChart::class,
            \App\Filament\Widgets\MaintenanceTypeDistributionChart::class,
            \App\Filament\Widgets\MaintenanceStatusDistributionChart::class,
        ];
    }

    /**
     * Alt kısımdaki rapor tablosu query
     */
    protected function getTableQuery()
    {
        return VehicleMaintenance::query()->with(['vehicle', 'maintenanceType']);
    }

    /**
     * Tablo kolonları
     */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('vehicle.plate')
                ->label('Araç')
                ->searchable(),

            Tables\Columns\TextColumn::make('maintenanceType.name')
                ->label('Bakım Türü')
                ->searchable(),

            Tables\Columns\TextColumn::make('maintenance_date')
                ->label('Tarih')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('km')
                ->label('KM')
                ->sortable(),

            Tables\Columns\TextColumn::make('cost')
                ->label('Tutar')
                ->money('TRY')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Durum')
                ->colors([
                    'warning' => 'planned',
                    'success' => 'completed',
                    'danger'  => 'cancelled',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'planned'   => 'Planlandı',
                    'completed' => 'Yapıldı',
                    'cancelled' => 'İptal',
                    default     => $state,
                }),
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
    }
}