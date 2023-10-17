<?php

namespace App\Filament\Resources\StatuscategoryResource\Pages;

use App\Filament\Resources\StatuscategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatuscategory extends EditRecord
{
    protected static string $resource = StatuscategoryResource::class;

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
