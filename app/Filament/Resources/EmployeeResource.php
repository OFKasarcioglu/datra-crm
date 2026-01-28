<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'İnsan Kaynakları';
    protected static ?string $navigationLabel = 'Çalışanlar';
    protected static ?string $modelLabel = 'Çalışan';
    protected static ?string $pluralModelLabel = 'Çalışanlar';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            /* -------- KİMLİK -------- */
            Forms\Components\Section::make('Kimlik Bilgileri')
                ->schema([
                    Forms\Components\TextInput::make('sicil_no')
                        ->label('Sicil No')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('first_name')
                        ->label('Ad')
                        ->required(),

                    Forms\Components\TextInput::make('last_name')
                        ->label('Soyad')
                        ->required(),

                    Forms\Components\TextInput::make('tc_no')
                        ->label('TC Kimlik No')
                        ->maxLength(11)
                         ->required(),

                    Forms\Components\DatePicker::make('birth_date')
                        ->label('Doğum Tarihi'),

                    Forms\Components\Select::make('gender')
                        ->label('Cinsiyet')
                        ->options([
                            'male' => 'Erkek',
                            'female' => 'Kadın',
                        ]),

                    Forms\Components\FileUpload::make('photo')
                        ->label('Fotoğraf')
                        ->directory('employees')
                        ->image()
                         ->columnSpanFull(),
                ])
                ->columns(3),

            /* -------- İŞ BİLGİLERİ -------- */
            Forms\Components\Section::make('İş Bilgileri')
                ->schema([
                    Forms\Components\Select::make('department_id')
                        ->label('Departman')
                        ->relationship('department', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('position_id')
                        ->label('Pozisyon')
                        ->relationship('position', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\DatePicker::make('hire_date')
                        ->label('İşe Giriş Tarihi')
                        ->required(),

                    Forms\Components\DatePicker::make('exit_date')
                        ->label('İşten Çıkış Tarihi'),

                   

                    Forms\Components\Select::make('employment_type')
                        ->label('Çalışma Şekli')
                        ->options([
                            'full' => 'Tam Zamanlı',
                            'part' => 'Yarı Zamanlı',
                            'outsourced' => 'Taşeron',
                        ])
                        ->default('full'),

                    Forms\Components\Select::make('employee_type')
                        ->label('Çalışan Tipi')
                        ->options([
                            'blue' => 'Mavi Yaka',
                            'white' => 'Beyaz Yaka',
                            'manager' => 'Yönetici',
                        ])
                        ->default('blue'),

                         
                ])
                ->columns(3),

            /* -------- İLETİŞİM -------- */
            Forms\Components\Section::make('İletişim Bilgileri')
    ->schema([

        // SATIR 1: Telefon + E-posta (50 / 50)
        Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('phone')
                    ->label('Telefon'),

                Forms\Components\TextInput::make('email')
                    ->label('E-posta'),
            ]),

        // SATIR 2: Adres (100%)
        Forms\Components\Textarea::make('address')
            ->label('Adres')
            ->columnSpanFull(),

        // SATIR 3: Şehir / İlçe / Acil Kişi (3 kolon)
        Forms\Components\Grid::make(3)
            ->schema([
                Forms\Components\TextInput::make('city')
                    ->label('Şehir'),

                Forms\Components\TextInput::make('district')
                    ->label('İlçe'),

                Forms\Components\TextInput::make('emergency_contact')
                    ->label('Acil Durum Kişisi'),
            ]),

        // SATIR 4: Acil Telefon (100%)
        Forms\Components\TextInput::make('emergency_phone')
            ->label('Acil Telefon')
            ->columnSpanFull(),
    ]),


            /* -------- SGK -------- */
            Forms\Components\Section::make('SGK / Sağlık')
                ->schema([
                    Forms\Components\TextInput::make('sgk_no')->label('SGK No'),

                    Forms\Components\DatePicker::make('sgk_start')
                        ->label('SGK Başlangıç'),

                    Forms\Components\DatePicker::make('sgk_end')
                        ->label('SGK Bitiş'),

                    Forms\Components\TextInput::make('blood_type')
                        ->label('Kan Grubu'),

                    Forms\Components\TextInput::make('disability_rate')
                        ->label('Engellilik Oranı (%)')
                        ->numeric(),

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
                ])
                ->columns(3),
        ]);
    }

    /* =======================
        TABLE
    ======================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sicil_no')
                    ->label('Sicil No')
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Ad Soyad')
                    ->searchable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departman')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('position.name')
                    ->label('Pozisyon')
                    ->badge(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Durum')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->label('Departman'),

                Tables\Filters\SelectFilter::make('position')
                    ->relationship('position', 'name')
                    ->label('Pozisyon'),

                Tables\Filters\TernaryFilter::make('status')
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
            ->emptyStateHeading('Çalışan Bulunamadı')
            ->emptyStateDescription('Henüz sisteme kayıtlı bir çalışan yok.');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
