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
        return [
            $booking->servicetype->description,
            $booking->boxtype->description,
            $booking->zone->description,
            $booking->total_amount,
            $booking->note,             
        ];
    }
    public function headings(): array
    {
        return [
            'Service',
            'Box',
            'Area',
            'Price',
            'Note',
        ];
    }
}
