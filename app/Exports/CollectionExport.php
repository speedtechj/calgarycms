<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\Boxtype;
use App\Models\Paymenttype;
use App\Models\Bookingpayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CollectionExport implements FromView
{
    use Exportable;
    public $booking;
    public $paymenttype;
    
    public function __construct(Collection $booking)
    {
       
        $this->booking = $booking;
      
    }
    
    public function view(): View
    {
        return view('export.collectionreport', [
            'booking' => $this->booking,
            'paymenttype' => Paymenttype::all(),
            'boxtype' => Boxtype::all(),
           
        ]);
      
    }
   
}
