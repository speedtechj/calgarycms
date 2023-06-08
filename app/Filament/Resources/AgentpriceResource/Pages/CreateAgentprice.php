<?php

namespace App\Filament\Resources\AgentpriceResource\Pages;

use App\Filament\Resources\AgentpriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgentprice extends CreateRecord
{
    protected static string $resource = AgentpriceResource::class;
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
