<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesQuoteResource\Pages;
use App\Models\SalesQuote;
use App\Models\CompanySetting;
use App\Models\PlanCustomer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesQuoteResource extends Resource
{
    protected static ?string $model = SalesQuote::class;

    /* =======================
        NAVIGATION
    ======================= */
    protected static ?string $navigationGroup = 'Satış & CRM';
    protected static ?string $navigationLabel = 'Teklifler';
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?int    $navigationSort  = 1;

    protected static ?string $pluralModelLabel = 'Teklifler';
    protected static ?string $modelLabel       = 'Teklif';

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        $setting = CompanySetting::first();

        return $form->schema([

            /* -------- TEKLİF BİLGİLERİ -------- */
            Forms\Components\Section::make('Teklif Bilgileri')
                ->schema([

                    Forms\Components\TextInput::make('quote_no')
                        ->label('Teklif No')
                        ->disabled()
                        ->dehydrated(),

                    Forms\Components\DatePicker::make('quote_date')
                        ->label('Teklif Tarihi')
                        ->default(now())
                        ->required(),

                    Forms\Components\Select::make('customer_id')
                        ->label('Müşteri')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $customer = PlanCustomer::find($state);

                            if ($customer) {
                                $set('customer_name',  $customer->name);
                                $set('contact_person', $customer->contact1_name);
                                $set('contact_phone',  $customer->contact1_phone);
                                $set('contact_email',  $customer->contact1_email);
                            }
                        }),

                    Forms\Components\TextInput::make('customer_name')
                        ->label('Müşteri Adı')
                        ->disabled()
                        ->dehydrated(),

                    /* 🔴 KRİTİK ALANLAR – GÖRÜNMEZ AMA KAYDEDİLİR */
                    Forms\Components\TextInput::make('contact_person')
                        ->dehydrated()
                        ->hidden(),

                    Forms\Components\TextInput::make('contact_phone')
                        ->dehydrated()
                        ->hidden(),

                    Forms\Components\TextInput::make('contact_email')
                        ->dehydrated()
                        ->hidden(),

                ])
                ->columns(2),

            /* -------- FİNANS -------- */
            Forms\Components\Section::make('Finans')
                ->schema([

                    Forms\Components\Select::make('currency')
                        ->label('Para Birimi')
                        ->options([
                            'TRY' => 'TRY',
                            'EUR' => 'EUR',
                            'USD' => 'USD',
                        ])
                        ->default($setting?->default_currency)
                        ->required(),

                    Forms\Components\Select::make('vat_rate')
                        ->label('KDV (%)')
                        ->options([
                            0  => '%0',
                            1  => '%1',
                            8  => '%8',
                            10 => '%10',
                            18 => '%18',
                            20 => '%20',
                        ])
                        ->default($setting?->default_vat_rate)
                        ->required(),

                ])
                ->columns(2),

            /* -------- KALEMLER -------- */
            Forms\Components\Section::make('Teklif Kalemleri')
                ->schema([

                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->schema([

                            Forms\Components\TextInput::make('description')
                                ->label('Açıklama')
                                ->required()
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('drawing_number')
                                ->label('Çizim No'),

                            Forms\Components\DatePicker::make('delivery_date')
                                ->label('Teslim Tarihi'),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Adet')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('unit_weight')
                                ->label('Birim KG')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Birim Fiyat (KG)')
                                ->numeric()
                                ->required(),

                        ])
                        ->columns(3)
                        ->defaultItems(1)
                        ->createItemButtonLabel('Kalem Ekle'),

                ]),

            /* -------- NOTLAR -------- */
            Forms\Components\Section::make('Notlar')
                ->schema([
                    Forms\Components\Textarea::make('note_1')->label('Not 1'),
                    Forms\Components\Textarea::make('note_2')->label('Not 2'),
                    Forms\Components\Textarea::make('note_3')->label('Not 3'),
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

                Tables\Columns\TextColumn::make('quote_no')
                    ->label('Teklif No')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Müşteri')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Genel Toplam')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('quote_date')
                    ->label('Tarih')
                    ->date('d.m.Y'),

            ])
            ->actions([

                Tables\Actions\Action::make('pdf')
                    ->label('PDF Al')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (SalesQuote $record) {

                        $setting = CompanySetting::first();

                        $pdf = Pdf::loadView('pdf.sales-quote', [
                            'quote'   => $record->load('items'),
                            'setting' => $setting,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $record->quote_no . '.pdf'
                        );
                    }),

                Tables\Actions\EditAction::make()->label('Düzenle'),

            ]);
    }

    /* =======================
        PAGES
    ======================= */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSalesQuotes::route('/'),
            'create' => Pages\CreateSalesQuote::route('/create'),
            'edit'   => Pages\EditSalesQuote::route('/{record}/edit'),
        ];
    }
}