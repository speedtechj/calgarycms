<?php

namespace App\Filament\Resources\CompanyinfoResource\Pages;

use App\Filament\Resources\CompanyinfoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyinfo extends EditRecord
{
    protected static string $resource = CompanyinfoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
