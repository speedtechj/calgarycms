<?php

namespace App\Filament\Resources\PaymenttypeResource\Pages;

use App\Filament\Resources\PaymenttypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymenttype extends EditRecord
{
    protected static string $resource = PaymenttypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
