<?php

namespace App\Exports;

use App\Models\Booking;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class ManilamanifestExport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    public $booking;
    
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
       
        $barangay =  $booking->receiveraddress->barangayphil->name ?? 'Request Canada Add Barangay';
        return [
            $booking->booking_invoice,
            $booking->manual_invoice,
            '1',
            $booking->boxtype->description,
            $booking->batch->batchno.' '. '-' .' '.$booking->batch->batch_year,
            $booking->sender->full_name,
            $booking->receiver->full_name,
            $booking->receiveraddress->address,
            $barangay,
            // $booking->receiveraddress->barangayphil->name,
            $booking->receiveraddress->cityphil->name,
            $booking->receiveraddress->provincephil->name,
            $booking->receiver->mobile_no,


                    
        ];
    }
    public function headings(): array
    {
        return [
            'Generated Invoice',
            'Manual Invoice',
            'Quantity',
            'Box Type',
            'Batch Number',
            'Sender Name',
            'Receiver Name',
            'Address',
            'Barangay',
            'City',
            'Province',
            'Mobile Number'     
        ];
    }
}
