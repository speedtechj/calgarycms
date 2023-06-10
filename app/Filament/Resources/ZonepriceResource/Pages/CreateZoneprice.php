<?php

namespace App\Filament\Resources\ZonepriceResource\Pages;

use App\Filament\Resources\ZonepriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZoneprice extends CreateRecord
{
    protected static string $resource = ZonepriceResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
