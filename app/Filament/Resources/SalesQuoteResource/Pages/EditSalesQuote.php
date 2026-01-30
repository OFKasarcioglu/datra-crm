<?php

namespace App\Filament\Resources\SalesQuoteResource\Pages;

use App\Filament\Resources\SalesQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesQuote extends EditRecord
{
    protected static string $resource = SalesQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
