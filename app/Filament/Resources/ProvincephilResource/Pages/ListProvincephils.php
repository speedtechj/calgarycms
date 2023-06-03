<?php

namespace App\Filament\Resources\ProvincephilResource\Pages;

use App\Filament\Resources\ProvincephilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProvincephils extends ListRecords
{
    protected static string $resource = ProvincephilResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
