<?php

namespace App\Filament\Resources\CatextrachargeResource\Pages;

use App\Filament\Resources\CatextrachargeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCatextracharge extends CreateRecord
{
    protected static string $resource = CatextrachargeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
