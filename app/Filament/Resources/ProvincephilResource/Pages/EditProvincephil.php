<?php

namespace App\Filament\Resources\ProvincephilResource\Pages;

use App\Filament\Resources\ProvincephilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvincephil extends EditRecord
{
    protected static string $resource = ProvincephilResource::class;

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
