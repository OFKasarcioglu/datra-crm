<?php

namespace App\Filament\Resources\PlanCustomerResource\Pages;

use App\Filament\Resources\PlanCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanCustomers extends ListRecords
{
    protected static string $resource = PlanCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
