<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceTypeResource\Pages;
use App\Models\MaintenanceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenanceTypeResource extends Resource
{
    protected static ?string $model = MaintenanceType::class;

    protected static ?string $navigationLabel = 'Araç Bakım Türleri';
    protected static ?string $navigationGroup = 'Araç Yönetimi';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'Bakım Türü';
    protected static ?string $pluralModelLabel = 'Bakım Türleri';

 public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Bakım Türü Adı')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(), // 👈 FULL WIDTH

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->onIcon('heroicon-o-check-circle')   // 👈 aktif icon
                ->offIcon('heroicon-o-x-circle')      // 👈 pasif icon
                ->onColor('success')                  // 👈 yeşil
                ->offColor('danger'),                 // 👈 kırmızı
        ])
        ->columns(2); // grid düzeni, toggle sağda güzel durur
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Bakım Türü')
                ->searchable()
                ->sortable(),

            Tables\Columns\ToggleColumn::make('is_active')
    ->label('Aktif')
    ->onIcon('heroicon-o-check-circle')
    ->offIcon('heroicon-o-x-circle')
    ->onColor('success')
    ->offColor('danger'), 
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Aktiflik Durumu')
                ->trueLabel('Aktif')
                ->falseLabel('Pasif')
                ->placeholder('Tümü'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('Düzenle')
            ->color('warning'),
            Tables\Actions\DeleteAction::make()->label('Sil'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
        ])
        ->emptyStateHeading('Bakım Türü Yok')
        ->emptyStateDescription('Henüz tanımlanmış bir bakım türü bulunmuyor.');
}

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMaintenanceTypes::route('/'),
            'create' => Pages\CreateMaintenanceType::route('/create'),
            'edit'   => Pages\EditMaintenanceType::route('/{record}/edit'),
        ];
    }
}