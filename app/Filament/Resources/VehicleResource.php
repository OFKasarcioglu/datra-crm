<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationLabel = 'Araçlar';
protected static ?string $navigationGroup = 'Araç Yönetimi';
protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $modelLabel = 'Araç';
    protected static ?string $pluralModelLabel = 'Araçlar';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('plate')
                    ->label('Plaka')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('brand')
                    ->label('Marka'),

                Forms\Components\TextInput::make('model')
                    ->label('Model'),

                Forms\Components\TextInput::make('year')
                    ->label('Yıl')
                    ->numeric(),

                Forms\Components\TextInput::make('current_km')
                    ->label('Mevcut KM')
                    ->numeric()
                    ->default(0)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->onIcon('heroicon-o-check-circle')
                ->offIcon('heroicon-o-x-circle')
                ->onColor('success')
                ->offColor('danger'),
            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('plate')
                ->label('Plaka')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('brand')
                ->label('Marka')
                ->searchable(),

            Tables\Columns\TextColumn::make('model')
                ->label('Model')
                ->searchable(),

            Tables\Columns\TextColumn::make('current_km')
                ->label('KM')
                ->sortable(),

            // ✅ İKONLU + TIKLANABİLİR TOGGLE
            Tables\Columns\ToggleColumn::make('is_active')
                ->label('Aktif')
                ->onIcon('heroicon-o-check-circle')
                ->offIcon('heroicon-o-x-circle')
                ->onColor('success')
                ->offColor('danger'),
        ])
        ->filters([
            // (Opsiyonel ama önerilir)
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Durum')
                ->trueLabel('Aktif')
                ->falseLabel('Pasif')
                ->placeholder('Tümü'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Düzenle')
                ->color('warning'),

            // ✅ TEKİL SİL
            Tables\Actions\DeleteAction::make()
                ->label('Sil')
                ->requiresConfirmation()
                ->modalHeading('Aracı Sil')
                ->modalDescription('Bu aracı silmek istediğinize emin misiniz?'),
        ])
        ->bulkActions([
            // ✅ TOPLU SİL
            Tables\Actions\DeleteBulkAction::make()
                ->label('Seçilenleri Sil')
                ->requiresConfirmation()
                ->modalHeading('Araçları Sil')
                ->modalDescription('Seçilen araçlar silinecek. Emin misiniz?'),
        ])
        ->emptyStateHeading('Araç Yok')
        ->emptyStateDescription('Henüz tanımlanmış bir araç bulunmuyor.');
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}