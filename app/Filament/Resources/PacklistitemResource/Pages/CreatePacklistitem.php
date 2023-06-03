<?php

namespace App\Filament\Resources\PacklistitemResource\Pages;

use App\Filament\Resources\PacklistitemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePacklistitem extends CreateRecord
{
    protected static string $resource = PacklistitemResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        // $data['branch_id'] = auth()->user()->branch_id;
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
