<?php

namespace App\Filament\Resources\TrackstatusResource\Pages;

use App\Filament\Resources\TrackstatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrackstatuses extends ListRecords
{
    protected static string $resource = TrackstatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
