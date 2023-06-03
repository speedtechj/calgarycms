<?php

namespace App\Filament\Resources\BoxtypeResource\Pages;

use App\Filament\Resources\BoxtypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoxtype extends EditRecord
{
    protected static string $resource = BoxtypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
