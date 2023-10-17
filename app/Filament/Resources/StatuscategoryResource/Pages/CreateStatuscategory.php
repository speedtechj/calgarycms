<?php

namespace App\Filament\Resources\StatuscategoryResource\Pages;

use App\Filament\Resources\StatuscategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStatuscategory extends CreateRecord
{
    protected static string $resource = StatuscategoryResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        
        
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
