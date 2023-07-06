<?php

namespace App\Http\Controllers;



use App\Models\Booking;
use App\Models\Citycan;
use App\Models\Cityphil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class HomeController extends Controller
{
    public function index(Booking $record){
        $data['record'] = $record;
        $pdf = PDF::loadView("invoice-pdf", $data);
        $pdf->setOption('margin-top','5mm');
         return $pdf->inline();
      
    }
    public function generate(Booking $record){

        $pdf = PDF::loadView("barcode", compact('record'));
        return $pdf->inline();
        //return view('barcode');
    }
}
