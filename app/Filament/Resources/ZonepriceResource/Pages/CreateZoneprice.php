<?php

namespace App\Filament\Resources\ZonepriceResource\Pages;

use App\Filament\Resources\ZonepriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZoneprice extends CreateRecord
{
    protected static string $resource = ZonepriceResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

       
         $data['price'] = $data['price'] * 100;
        
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
