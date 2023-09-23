<?php

namespace App\Filament\Resources\TrackstatusResource\Pages;

use App\Filament\Resources\TrackstatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrackstatus extends CreateRecord
{
    protected static string $resource = TrackstatusResource::class;
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
