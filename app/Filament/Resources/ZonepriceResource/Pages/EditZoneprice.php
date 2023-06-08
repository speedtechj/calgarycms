<?php

namespace App\Filament\Resources\ZonepriceResource\Pages;

use App\Filament\Resources\ZonepriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZoneprice extends EditRecord
{
    protected static string $resource = ZonepriceResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['price'] = $data['price'] / 100;
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['price'] = $data['price'] * 100;
        return $data;
    }

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
