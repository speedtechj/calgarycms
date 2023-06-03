<?php

namespace App\Filament\Resources\ReceiveraddressResource\Pages;

use App\Filament\Resources\ReceiveraddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiveraddress extends EditRecord
{
    protected static string $resource = ReceiveraddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
