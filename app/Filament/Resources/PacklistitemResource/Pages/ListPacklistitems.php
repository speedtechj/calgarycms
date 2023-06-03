<?php

namespace App\Filament\Resources\PacklistitemResource\Pages;

use App\Filament\Resources\PacklistitemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacklistitems extends ListRecords
{
    protected static string $resource = PacklistitemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
