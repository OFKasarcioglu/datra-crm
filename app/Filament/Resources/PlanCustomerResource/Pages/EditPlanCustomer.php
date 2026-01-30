<?php

namespace App\Filament\Resources\PlanCustomerResource\Pages;

use App\Filament\Resources\PlanCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanCustomer extends EditRecord
{
    protected static string $resource = PlanCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
