<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationLabel = 'Tedarikçiler';
    protected static ?string $modelLabel = 'Tedarikçi';
    protected static ?string $pluralModelLabel = 'Tedarikçiler';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Satın Alma';
    protected static ?int    $navigationSort  = 1;

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            /* -------- TEMEL -------- */
            Forms\Components\Section::make('Temel Bilgiler')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('supplier_code')
                            ->label('Tedarikçi Kodu')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('name')
                            ->label('Firma Adı')
                            ->required(),

                        Forms\Components\TextInput::make('short_title')
                            ->label('Kısa Ad'),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Resmi Ünvan'),

                        Forms\Components\TextInput::make('tax_number')
                            ->label('Vergi No')
                            ->unique(ignoreRecord: true),
                    ]),
                ]),

            /* -------- İLETİŞİM -------- */
            Forms\Components\Section::make('İletişim')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('phone')->label('Telefon'),
                        Forms\Components\TextInput::make('email')->label('E-posta'),
                    ]),

                    Forms\Components\Textarea::make('address')
                        ->label('Adres')
                        ->columnSpanFull(),
                ]),

            /* -------- FİNANS -------- */
            Forms\Components\Section::make('Finans')
                ->schema([
                    Forms\Components\TextInput::make('currency')
                        ->label('Para Birimi')
                        ->default('TRY'),

                    Forms\Components\TextInput::make('payment_term')
                        ->label('Vade (Gün)')
                        ->numeric(),

                    Forms\Components\TextInput::make('credit_limit')
                        ->label('Kredi Limiti')
                        ->numeric(),
                ])
                ->columns(3),

            /* -------- DURUM -------- */
            Forms\Components\Section::make('Durum')
                ->schema([
                    ToggleButtons::make('is_active')
                        ->label('Aktiflik')
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
                ]),
        ]);
    }

    /* =======================
        TABLE
    ======================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier_code')
                    ->label('Kod')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Tedarikçi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('PB')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Durum')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit'   => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
