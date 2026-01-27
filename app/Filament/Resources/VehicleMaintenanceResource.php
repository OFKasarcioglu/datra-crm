<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleMaintenanceResource\Pages;
use App\Models\VehicleMaintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleMaintenanceResource extends Resource
{
    protected static ?string $model = VehicleMaintenance::class;

    protected static ?string $navigationLabel = 'Araç Bakımları';
protected static ?string $navigationGroup = 'Araç Yönetimi';
protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $modelLabel = 'Araç Bakımı';
    protected static ?string $pluralModelLabel = 'Araç Bakımları';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Select::make('vehicle_id')
    ->label('Araç')
    ->relationship(
        name: 'vehicle',
        titleAttribute: 'plate',
        modifyQueryUsing: fn ($query) => $query->where('is_active', true)
    )
    ->searchable()
    ->preload()   // 👈 açılınca tüm araçları getirir
    ->required(),

                Forms\Components\Select::make('maintenance_type_id')
    ->label('Bakım Türü')
    ->relationship(
        name: 'maintenanceType',
        titleAttribute: 'name',
        modifyQueryUsing: fn ($query) => $query->where('is_active', true)
    )
    ->searchable()
    ->preload() 
    ->required(),

                Forms\Components\DatePicker::make('maintenance_date')
                    ->label('Bakım Tarihi')
                    ->required(),

                Forms\Components\TextInput::make('km')
                    ->label('KM')
                    ->numeric(),

                Forms\Components\DatePicker::make('next_maintenance_date')
                    ->label('Bir Sonraki Bakım Tarihi'),

                Forms\Components\TextInput::make('next_km')
                    ->label('Bir Sonraki KM')
                    ->numeric(),

                Forms\Components\TextInput::make('cost')
                    ->label('Tutar')
                    ->numeric()
                    ->prefix('₺'),

                Forms\Components\TextInput::make('service_name')
                    ->label('Servis / Firma'),

                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label('Durum')
                    ->options([
                        'planned'   => 'Planlandı',
                        'completed' => 'Yapıldı',
                        'cancelled' => 'İptal',
                    ])
                    ->default('planned')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            // 🔍 Araç (plaka) – searchable
            Tables\Columns\TextColumn::make('vehicle.plate')
                ->label('Araç')
                ->searchable()
                ->sortable(),

            // 🔍 Bakım Türü – searchable
            Tables\Columns\TextColumn::make('maintenanceType.name')
                ->label('Bakım Türü')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('maintenance_date')
                ->label('Bakım Tarihi')
                ->date()
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
                }),
        ])
        ->filters([
            // 🚗 Araç filtresi
            Tables\Filters\SelectFilter::make('vehicle_id')
                ->label('Araç')
                ->relationship('vehicle', 'plate')
                ->searchable()
                ->preload(),

            // 🔧 Bakım Türü filtresi
            Tables\Filters\SelectFilter::make('maintenance_type_id')
                ->label('Bakım Türü')
                ->relationship('maintenanceType', 'name')
                ->searchable()
                ->preload(),

            // 🟢 Durum filtresi
            Tables\Filters\SelectFilter::make('status')
                ->label('Durum')
                ->options([
                    'planned'   => 'Planlandı',
                    'completed' => 'Yapıldı',
                    'cancelled' => 'İptal',
                ]),

            // 📅 Tarih aralığı filtresi
            Tables\Filters\Filter::make('maintenance_date')
                ->label('Bakım Tarihi')
                ->form([
                    Forms\Components\DatePicker::make('from')
                        ->label('Başlangıç'),
                    Forms\Components\DatePicker::make('until')
                        ->label('Bitiş'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['from'],
                            fn ($q) => $q->whereDate('maintenance_date', '>=', $data['from'])
                        )
                        ->when(
                            $data['until'],
                            fn ($q) => $q->whereDate('maintenance_date', '<=', $data['until'])
                        );
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('Düzenle')
            ->color('warning'),
            Tables\Actions\DeleteAction::make()->label('Sil'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
        ])
        ->emptyStateHeading('Bakım Kaydı Yok')
        ->emptyStateDescription('Henüz girilmiş bir araç bakımı bulunmuyor.');
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleMaintenances::route('/'),
            'create' => Pages\CreateVehicleMaintenance::route('/create'),
            'edit' => Pages\EditVehicleMaintenance::route('/{record}/edit'),
        ];
    }
}