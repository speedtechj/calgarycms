<?php

namespace App\Http\Controllers;

use data;
use App\Models\Booking;
use App\Models\Companyinfo;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PackinglistpdfController extends Controller
{
    public function index(Booking $record){
        $companyinfo = Companyinfo::all()->first();
        // $packinglistdata = Packinglist::where('booking_id', $record->id)->get();
        $data['record'] = $record;
        $data['companyinfo'] = $companyinfo;
        // $data['packinglist'] = $packinglistdata;
        // $data['paymenttype'] = Paymenttype::all();
         
        $pdf = PDF::loadView("packlistpdf", $data);
        $pdf->setOption('margin-top','5mm');
        $pdf->setOption('margin-bottom','5mm');
        $pdf->setOption('margin-right','5mm');
        $pdf->setOption('margin-left','5mm');
         return $pdf->inline();
      
    }
    
}
