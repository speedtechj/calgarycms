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
        $data['record'] = $record;
        $pdf = PDF::loadView("invoice-pdf", $data);
        $header = view()->make('invoice-header', $data);
        $pdf->setOption('header-html', $header);
        
         return $pdf->inline();
      
    }
    public function generate(Booking $record){

        $pdf = PDF::loadView("barcode", compact('record'));
        return $pdf->inline();
        //return view('barcode');
    }
}
