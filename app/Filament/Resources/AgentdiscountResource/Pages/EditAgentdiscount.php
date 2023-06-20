<?php

namespace App\Filament\Resources\AgentdiscountResource\Pages;

use App\Filament\Resources\AgentdiscountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentdiscount extends EditRecord
{
    protected static string $resource = AgentdiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
