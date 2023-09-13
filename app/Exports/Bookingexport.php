<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class Bookingexport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    public $booking;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(Collection $booking)
    {
       
        $this->booking = $booking;
    }
    public function query()
    {
        return  Booking::wherekey($this->booking->pluck('id')->toArray());
       
        
    }
    public function map($booking): array
    {
        // dd($booking->senderaddress->citycan->name);
        return [
            $booking->sender->full_name,
            $booking->senderaddress->address,
            $booking->senderaddress->provincecan->name,
            $booking->senderaddress->citycan->name,
            $booking->senderaddress->postal_code,
            $booking->senderaddress->quadrant,
            $booking->sender->mobile_no,
            $booking->sender->home_no,
            $booking->start_time,
            $booking->end_time,
            $booking->zone->description,
            $booking->boxtype->description,
            $booking->total_price,
            $booking->note,             
        ];
    }
    public function headings(): array
    {
        return [
            'Full Name',
            'Address',
            'Province',
            'City',
            'Postal Code',
            'Quadrant',
            'Mobile No',
            'Home No',
            'start Time',
            'end Time',
            'Location',
            'Box Type',
            'Price',
            'Note',
        ];
    }
}
