<?php

namespace App\Filament\Resources\CatextrachargeResource\Pages;

use App\Filament\Resources\CatextrachargeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatextracharge extends EditRecord
{
    protected static string $resource = CatextrachargeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
}
