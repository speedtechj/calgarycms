<?php

namespace App\Filament\Resources\PacklistitemResource\Pages;

use App\Filament\Resources\PacklistitemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPacklistitem extends EditRecord
{
    protected static string $resource = PacklistitemResource::class;

    // protected function getActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
