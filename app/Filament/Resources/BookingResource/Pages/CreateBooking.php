<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    // $data['user_id'] = auth()->id();
    // $data['branch_id'] = 1;
    // // $data['zone_id'] = 1;
    //  return $data;
    // dd($data);
   
}
}
