<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\DepartmentResource\RelationManagers\PositionsRelationManager;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationGroup = 'İnsan Kaynakları';
    protected static ?string $navigationLabel = 'Departmanlar';
    protected static ?string $modelLabel = 'Departman';
    protected static ?string $pluralModelLabel = 'Departmanlar';
    protected static ?string $navigationIcon  = 'heroicon-o-building-office';

    /* =======================
        FORM
    ======================= */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Departman Adı')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->label('Kod')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->extraInputAttributes([
                        'style' => 'text-transform: uppercase',
                    ])
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
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Departman')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Durum')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturma Tarihi')
                    ->date('d.m.Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
{
    return [
        PositionsRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit'   => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
