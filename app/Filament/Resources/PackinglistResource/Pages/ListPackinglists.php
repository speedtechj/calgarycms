<?php

namespace App\Filament\Resources\PackinglistResource\Pages;

use App\Filament\Resources\PackinglistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackinglists extends ListRecords
{
    protected static string $resource = PackinglistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
