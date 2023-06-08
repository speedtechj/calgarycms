<?php

namespace App\Filament\Resources\AgentpriceResource\Pages;

use App\Filament\Resources\AgentpriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentprice extends EditRecord
{
    protected static string $resource = AgentpriceResource::class;


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['price'] = $data['price'] / 100;
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['price'] = $data['price'] * 100;
        return $data;
    }
}
