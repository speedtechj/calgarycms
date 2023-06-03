<?php

namespace App\Filament\Resources\SenderaddressResource\Pages;

use App\Filament\Resources\SenderaddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSenderaddresses extends ListRecords
{
    protected static string $resource = SenderaddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
