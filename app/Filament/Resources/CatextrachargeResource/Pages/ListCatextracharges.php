<?php

namespace App\Filament\Resources\CatextrachargeResource\Pages;

use App\Filament\Resources\CatextrachargeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatextracharges extends ListRecords
{
    protected static string $resource = CatextrachargeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
