<?php

namespace App\Filament\Resources\BarangayphilResource\Pages;

use App\Filament\Resources\BarangayphilResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangayphil extends EditRecord
{
    protected static string $resource = BarangayphilResource::class;

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
