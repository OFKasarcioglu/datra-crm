<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanCustomerResource\Pages;
use App\Models\PlanCustomer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanCustomerResource extends Resource
{
    protected static ?string $model = PlanCustomer::class;

    protected static ?string $navigationLabel = 'Müşteriler';
    protected static ?string $modelLabel = 'Müşteri';
    protected static ?string $pluralModelLabel = 'Müşteriler';
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Satış & CRM';
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

        // ÜST SATIR: 3 kolon
        Forms\Components\Grid::make(3)
            ->schema([
                Forms\Components\TextInput::make('customer_code')
                    ->label('Müşteri Kodu')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('customer_type')
                    ->label('Müşteri Tipi')
                    ->options([
                        'corporate'  => 'Kurumsal',
                        'individual' => 'Bireysel',
                        'dealer'     => 'Bayi',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Ad / Firma Adı')
                    ->required(),
            ]),

        // ALT SATIR: 2 kolon (KISA AD + RESMİ ÜNVAN)
        Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('short_title')
                    ->label('Kısa Ad'),

                Forms\Components\TextInput::make('title')
                    ->label('Resmi Ünvan'),
            ]),
    ]),

            /* -------- VERGİ -------- */
            Forms\Components\Section::make('Vergi Bilgileri')
                ->schema([
                    Forms\Components\TextInput::make('tax_number')
                        ->label('Vergi No')
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('tax_office')
                        ->label('Vergi Dairesi'),
                ])
                ->columns(2),

            /* -------- İLETİŞİM -------- */
            Forms\Components\Section::make('İletişim Bilgileri')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('phone')->label('Telefon'),
                        Forms\Components\TextInput::make('email')->label('E-posta'),
                    ]),

                    Forms\Components\Textarea::make('address')
                        ->label('Adres')
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('country')->label('Ülke'),
                        Forms\Components\TextInput::make('city')->label('Şehir'),
                        Forms\Components\TextInput::make('district')->label('İlçe'),
                    ]),
                ]),

            /* -------- YETKİLİLER -------- */
            Forms\Components\Section::make('Yetkili Bilgileri')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('contact1_name')->label('Yetkili Adı'),
                        Forms\Components\TextInput::make('contact1_phone')->label('Yetkili Telefon'),
                        Forms\Components\TextInput::make('contact1_email')->label('Yetkili E-posta'),
                    ]),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('contact2_name')->label('Yetkili 2 Adı'),
                        Forms\Components\TextInput::make('contact2_phone')->label('Yetkili 2 Telefon'),
                        Forms\Components\TextInput::make('contact2_email')->label('Yetkili 2 E-posta'),
                    ]),
                ]),

            /* -------- FİNANS -------- */
            Forms\Components\Section::make('Finans')
                ->schema([
                    Forms\Components\TextInput::make('currency')
                        ->label('Para Birimi')
                        ->default('TRY'),

                    Forms\Components\TextInput::make('credit_limit')
                        ->label('Kredi Limiti')
                        ->numeric(),
                ])
                ->columns(2),

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
                Tables\Columns\TextColumn::make('customer_code')
                    ->label('Kod')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Müşteri')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_type')
    ->label('Tip')
    ->badge()
    ->formatStateUsing(fn ($state) => match ($state) {
        'corporate'  => 'Kurumsal',
        'individual' => 'Bireysel',
        'dealer'     => 'Bayi',
        default      => $state,
    })
    ->colors([
        'primary' => 'corporate',
        'success' => 'dealer',
        'warning' => 'individual',
    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Durum')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_type')
                    ->label('Müşteri Tipi')
                    ->options([
                        'corporate'  => 'Kurumsal',
                        'individual' => 'Bireysel',
                        'dealer'     => 'Bayi',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Müşteri Bulunamadı')
            ->emptyStateDescription('Henüz sisteme kayıtlı müşteri yok.');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPlanCustomers::route('/'),
            'create' => Pages\CreatePlanCustomer::route('/create'),
            'edit'   => Pages\EditPlanCustomer::route('/{record}/edit'),
        ];
    }
}
