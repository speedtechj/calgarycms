<?php

namespace App\Filament\Resources\CityphilResource\Pages;

use App\Filament\Resources\CityphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityphils extends ListRecords
{
    protected static string $resource = CityphilResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
