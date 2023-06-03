<?php

namespace App\Filament\Resources\PaymenttypeResource\Pages;

use App\Filament\Resources\PaymenttypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymenttypes extends ListRecords
{
    protected static string $resource = PaymenttypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
