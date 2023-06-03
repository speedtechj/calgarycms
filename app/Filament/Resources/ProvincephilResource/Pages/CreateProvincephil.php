<?php

namespace App\Filament\Resources\ProvincephilResource\Pages;

use App\Filament\Resources\ProvincephilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProvincephil extends CreateRecord
{
    protected static string $resource = ProvincephilResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
