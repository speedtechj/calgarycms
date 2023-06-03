<?php

namespace App\Filament\Resources\ServicetypeResource\Pages;

use App\Filament\Resources\ServicetypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicetype extends EditRecord
{
    protected static string $resource = ServicetypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
