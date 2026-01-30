<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySettingResource\Pages;
use App\Models\CompanySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanySettingResource extends Resource
{
    protected static ?string $model = CompanySetting::class;

    /* =======================
        NAVIGATION
    ======================= */
    protected static ?string $navigationGroup = 'Satış & CRM';
    protected static ?int    $navigationSort  = 10;
    protected static ?string $navigationLabel = 'Satış Ayarları';
    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $modelLabel = 'Satış Ayarları';
    protected static ?string $pluralModelLabel = 'Satış Ayarları';


    public static function canCreate(): bool
    {
        return false;
    }

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            /* -------- FİRMA -------- */
            Forms\Components\Section::make('Firma Bilgileri')
                ->schema([
                    Forms\Components\TextInput::make('company_name')
                        ->label('Firma Adı')
                        ->required(),

                    

                    Forms\Components\TextInput::make('tax_office')
                        ->label('Vergi Dairesi'),

                    Forms\Components\TextInput::make('tax_number')
                        ->label('Vergi No'),

                    Forms\Components\TextInput::make('phone')
                        ->label('Telefon'),

                    Forms\Components\TextInput::make('email')
                        ->label('E-posta'),

                    Forms\Components\TextInput::make('website')
                        ->label('Web Sitesi'),

                    Forms\Components\FileUpload::make('logo')
                        ->label('Firma Logosu')
                        ->directory('company')
                        ->image(),

                    Forms\Components\Textarea::make('address')
                    ->label('Adres'),
                ])
                ->columns(2),

            /* -------- PROFORMA -------- */
Forms\Components\Section::make('Teklif / Proforma Ayarları')
    ->schema([

        // Varsayılan Para Birimi
        Forms\Components\Select::make('default_currency')
            ->label('Varsayılan Para Birimi')
            ->options([
                'TRY' => 'TRY - Türk Lirası',
                'EUR' => 'EUR - Euro',
                'USD' => 'USD - Amerikan Doları',
                'GBP' => 'GBP - İngiliz Sterlini',
            ])
            ->required(),

        // Varsayılan KDV
        Forms\Components\Select::make('default_vat_rate')
            ->label('Varsayılan KDV (%)')
            ->options([
                0  => '%0',
                1  => '%1',
                8  => '%8',
                10 => '%10',
                18 => '%18',
                20 => '%20',
            ])
            ->required(),

        // Teklif Ön Eki
        Forms\Components\Select::make('quote_prefix')
            ->label('Teklif Ön Eki')
            ->options([
                'DS' => 'DS',
                'PF' => 'PF',
                'PR' => 'PR',
            ])
            ->required(),

        // Başlangıç No
        Forms\Components\Select::make('quote_start_number')
            ->label('Başlangıç No')
            ->options(
                collect(range(1000, 9999, 100))
                    ->mapWithKeys(fn ($v) => [$v => $v])
                    ->toArray()
            )
            ->required(),

        // Yıl Formatı
        Forms\Components\Select::make('quote_year_format')
            ->label('Yıl Formatı')
            ->options([
                'YY'   => '26 (YY)',
                'YYYY' => '2026 (YYYY)',
            ])
            ->required()
            ->columnSpanFull(),

    ])
    ->columns(2),


            /* -------- NOTLAR -------- */
            Forms\Components\Section::make('Varsayılan Notlar')
                ->schema([
                    Forms\Components\Textarea::make('note_1')->label('Not 1'),
                    Forms\Components\Textarea::make('note_2')->label('Not 2'),
                    Forms\Components\Textarea::make('note_3')->label('Not 3'),
                    Forms\Components\Textarea::make('note_4')->label('Not 4'),
                    Forms\Components\Textarea::make('note_5')->label('Not 5'),
                ]),
        ]);
    }

    /* =======================
        TABLE (MODAL YOK)
    ======================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Firma'),

                Tables\Columns\TextColumn::make('default_currency')
                    ->label('PB'),

                Tables\Columns\TextColumn::make('default_vat_rate')
                    ->label('KDV'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('warning')
                    ->url(fn ($record) => static::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([]); // silme / toplu işlem yok
    }

    /* =======================
        PAGES
    ======================= */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanySettings::route('/'),
            'edit'  => Pages\EditCompanySetting::route('/{record}/edit'),
        ];
    }
}
