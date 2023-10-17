<?php

namespace App\Filament\Resources\StatuscategoryResource\Pages;

use App\Filament\Resources\StatuscategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatuscategories extends ListRecords
{
    protected static string $resource = StatuscategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
