<?php

namespace App\Filament\Resources\TrackstatusResource\Pages;

use App\Filament\Resources\TrackstatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrackstatus extends EditRecord
{
    protected static string $resource = TrackstatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
