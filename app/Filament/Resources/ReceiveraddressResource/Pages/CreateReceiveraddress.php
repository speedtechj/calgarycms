<?php

namespace App\Filament\Resources\ReceiveraddressResource\Pages;

use App\Filament\Resources\ReceiveraddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReceiveraddress extends CreateRecord
{
    protected static string $resource = ReceiveraddressResource::class;
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['user_id'] = auth()->id();

    //     return $data;

    // }
    protected function getRedirectUrl(): string

    {
        return $this->getResource()::getUrl('index');
    }
}
