<?php

namespace App\Http\Controllers;



use App\Models\Booking;
use App\Models\Citycan;
use App\Models\Cityphil;
use App\Models\Packinglist;
use App\Models\Paymenttype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class HomeController extends Controller
{
    public function index(Booking $record){
        $packinglistdata = Packinglist::where('booking_id', $record->id)->get();
        $data['record'] = $record;
        $data['packinglist'] = $packinglistdata;
        $data['paymenttype'] = Paymenttype::all();
       
        $pdf = PDF::loadView("invoice-pdf", $data);
        $pdf->setOption('margin-top','5mm');
        $pdf->setOption('margin-bottom','5mm');
        $pdf->setOption('margin-right','5mm');
        $pdf->setOption('margin-left','5mm');
         return $pdf->inline();
      
    }
    public function generate(Booking $record){

        $pdf = PDF::loadView("barcode", compact('record'));
        return $pdf->inline();
        //return view('barcode');
    }
}
