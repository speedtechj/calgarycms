<?php

namespace App\Filament\Resources\CitycanResource\Pages;

use App\Filament\Resources\CitycanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitycans extends ListRecords
{
    protected static string $resource = CitycanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
