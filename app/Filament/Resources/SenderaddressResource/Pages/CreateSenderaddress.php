<?php

namespace App\Filament\Resources\SenderaddressResource\Pages;

use App\Filament\Resources\SenderaddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSenderaddress extends CreateRecord
{
    protected static string $resource = SenderaddressResource::class;
     
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
