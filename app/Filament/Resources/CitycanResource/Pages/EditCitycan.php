<?php

namespace App\Filament\Resources\CitycanResource\Pages;

use App\Filament\Resources\CitycanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCitycan extends EditRecord
{
    protected static string $resource = CitycanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
