<?php

namespace App\Filament\Resources\ReceiverResource\Pages;

use App\Filament\Resources\ReceiverResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceivers extends ListRecords
{
    protected static string $resource = ReceiverResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
