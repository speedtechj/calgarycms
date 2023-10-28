<?php

namespace App\Http\Controllers;

use data;
use App\Models\Booking;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;

class PackinglistpdfController extends Controller
{
    public function index(Booking $record){
        
        // $packinglistdata = Packinglist::where('booking_id', $record->id)->get();
        $data['record'] = $record;
        // $data['packinglist'] = $packinglistdata;
        // $data['paymenttype'] = Paymenttype::all();
         
        $pdf = PDF::loadView("packlistpdf", $data);
        $pdf->setOption('margin-top','20mm');
        $pdf->setOption('margin-bottom','10mm');
        $pdf->setOption('margin-right','10mm');
        $pdf->setOption('margin-left','10mm');
         return $pdf->inline();
      
    }
    
}
