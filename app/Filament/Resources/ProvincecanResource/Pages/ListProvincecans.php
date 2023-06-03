<?php

namespace App\Filament\Resources\ProvincecanResource\Pages;

use App\Filament\Resources\ProvincecanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProvincecans extends ListRecords
{
    protected static string $resource = ProvincecanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
