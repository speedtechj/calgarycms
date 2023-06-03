<?php

namespace App\Filament\Resources\BarangayphilResource\Pages;

use App\Filament\Resources\BarangayphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangayphil extends CreateRecord
{
    protected static string $resource = BarangayphilResource::class;
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['user_id'] = auth()->id();

    //     return $data;

    // }
    protected function getRedirectUrl(): string

    {
        return $this->getResource()::getUrl('index');
    }
}
