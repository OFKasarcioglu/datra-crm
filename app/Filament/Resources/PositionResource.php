<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationGroup = 'İnsan Kaynakları';
    protected static ?string $navigationLabel = 'Pozisyonlar';
    protected static ?string $modelLabel = 'Pozisyon';
    protected static ?string $pluralModelLabel = 'Pozisyonlar';
    protected static ?string $navigationIcon  = 'heroicon-o-identification';
    protected static ?int    $navigationSort  = 2;

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('department_id')
                ->label('Departman')
                ->relationship('department', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('name')
                ->label('Pozisyon Adı')
                ->required(),

            Forms\Components\TextInput::make('code')
                ->label('Kod')
                ->required()
                ->unique(ignoreRecord: true)
                ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

            Forms\Components\Textarea::make('description')
                ->label('Açıklama')
                ->columnSpanFull(),

            ToggleButtons::make('status')
                ->label('Durum')
                ->boolean()
                ->grouped()
                ->icons([
                    true  => 'heroicon-o-check-circle',
                    false => 'heroicon-o-x-circle',
                ])
                ->colors([
                    true  => 'success',
                    false => 'danger',
                ])
                ->default(true),
        ]);
    }

    /* =======================
        TABLE
    ======================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departman')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->badge()
                    ->color('secondary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Pozisyon')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Durum')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
            ])

            /* -------- FILTERS -------- */
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->label('Departman')
                    ->relationship('department', 'name'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Durum')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),
            ])

            /* -------- ROW ACTIONS -------- */
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])

            /* -------- BULK ACTIONS -------- */
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ])

            /* -------- EMPTY STATE -------- */
            ->emptyStateHeading('Pozisyon Bulunamadı')
            ->emptyStateDescription('Henüz sisteme tanımlı bir pozisyon yok.');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit'   => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
