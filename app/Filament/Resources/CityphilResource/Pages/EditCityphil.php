<?php

namespace App\Filament\Resources\CityphilResource\Pages;

use App\Filament\Resources\CityphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityphil extends EditRecord
{
    protected static string $resource = CityphilResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
