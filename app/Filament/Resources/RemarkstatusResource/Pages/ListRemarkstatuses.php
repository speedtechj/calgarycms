<?php

namespace App\Filament\Resources\RemarkstatusResource\Pages;

use App\Filament\Resources\RemarkstatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRemarkstatuses extends ListRecords
{
    protected static string $resource = RemarkstatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
