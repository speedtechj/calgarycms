<?php

namespace App\Filament\Resources\AgentpriceResource\Pages;

use App\Filament\Resources\AgentpriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentprice extends EditRecord
{
    protected static string $resource = AgentpriceResource::class;


    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
