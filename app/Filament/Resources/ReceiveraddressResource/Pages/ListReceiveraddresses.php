<?php

namespace App\Filament\Resources\ReceiveraddressResource\Pages;

use App\Filament\Resources\ReceiveraddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiveraddresses extends ListRecords
{
    protected static string $resource = ReceiveraddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
