<?php

namespace App\Filament\Resources\BarangayphilResource\Pages;

use App\Filament\Resources\BarangayphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangayphils extends ListRecords
{
    protected static string $resource = BarangayphilResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
