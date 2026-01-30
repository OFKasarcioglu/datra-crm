<?php

namespace App\Filament\Resources\SalesQuoteResource\Pages;

use App\Filament\Resources\SalesQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesQuotes extends ListRecords
{
    protected static string $resource = SalesQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
