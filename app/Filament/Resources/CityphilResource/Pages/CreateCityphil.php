<?php

namespace App\Filament\Resources\CityphilResource\Pages;

use App\Filament\Resources\CityphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCityphil extends CreateRecord
{
    protected static string $resource = CityphilResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
