<?php

namespace App\Filament\Resources\SenderaddressResource\Pages;

use App\Filament\Resources\SenderaddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSenderaddress extends EditRecord
{
    protected static string $resource = SenderaddressResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
