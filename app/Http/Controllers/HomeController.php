<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\Booking;
use App\Models\Citycan;
use App\Models\Cityphil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Booking $record){
       
        $temp2 = Cityphil::find($record->receiveraddress->cityphil_id)->first();
        //dd($temp2);
        $temp = Citycan::find($record->senderaddress->citycan_id)->first();
         //dd($temp);
        $pdf = PDF::loadView("invoice-pdf", compact('record','temp','temp2'));
         return $pdf->inline();
        //return view('welcome');
    }
    public function generate(Booking $record){

        $pdf = PDF::loadView("barcode", compact('record'));
        return $pdf->inline();
        //return view('barcode');
    }
}
