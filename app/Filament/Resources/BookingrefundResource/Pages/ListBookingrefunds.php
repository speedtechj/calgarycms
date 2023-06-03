<?php

namespace App\Filament\Resources\BookingrefundResource\Pages;

use App\Filament\Resources\BookingrefundResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingrefunds extends ListRecords
{
    protected static string $resource = BookingrefundResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
