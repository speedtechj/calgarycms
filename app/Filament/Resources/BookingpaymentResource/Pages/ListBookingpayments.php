<?php

namespace App\Filament\Resources\BookingpaymentResource\Pages;

use App\Filament\Resources\BookingpaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingpayments extends ListRecords
{
    protected static string $resource = BookingpaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
