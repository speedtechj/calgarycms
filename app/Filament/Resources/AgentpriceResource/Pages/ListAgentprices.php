<?php

namespace App\Filament\Resources\AgentpriceResource\Pages;

use App\Filament\Resources\AgentpriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentprices extends ListRecords
{
    protected static string $resource = AgentpriceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
