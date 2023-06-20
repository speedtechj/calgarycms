<?php

namespace App\Filament\Resources\AgentdiscountResource\Pages;

use App\Filament\Resources\AgentdiscountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgentdiscount extends CreateRecord
{
    protected static string $resource = AgentdiscountResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;

    }
}
