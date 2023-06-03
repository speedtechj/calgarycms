<?php

namespace App\Filament\Resources\PackinglistResource\Pages;

use App\Filament\Resources\PackinglistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackinglist extends EditRecord
{
    protected static string $resource = PackinglistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
