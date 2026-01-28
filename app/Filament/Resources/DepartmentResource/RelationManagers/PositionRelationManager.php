<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class PositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'positions';

    protected static ?string $title = 'Pozisyonlar';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Pozisyon Adı')
                ->required(),

            Forms\Components\TextInput::make('code')
                ->label('Kod')
                ->required(),

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

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->badge(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ]);
    }
}
