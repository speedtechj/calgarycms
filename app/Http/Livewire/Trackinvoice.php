<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Livewire\Component;
use App\Models\Invoicestatus;

class Trackinvoice extends Component
{
    public $search = "0000000";
    public function render()
    {
        return view('livewire.trackinvoice',[
            'booking' => Booking::where('booking_invoice',$this->search)
            ->orWhere('manual_invoice', $this->search)->first(),
            'invoicestatus' => Invoicestatus::where('generated_invoice',$this->search)
            ->orWhere('manual_invoice', $this->search)->get()->sortByDesc('date_update'),
            'currentstatus' => Invoicestatus::where('generated_invoice',$this->search)
            ->orWhere('manual_invoice', $this->search)->latest('date_update')->first(),
        ]);
    }
}
