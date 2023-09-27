<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Invoicestatus;

class Trackinvoice extends Component
{
    public $search = "0000000";
    public function render()
    {
        return view('livewire.trackinvoice',[
            'invoicestatus' => Invoicestatus::where('generated_invoice',$this->search)
            ->orWhere('manual_invoice', $this->search)->get(),
            'currentstatus' => Invoicestatus::where('generated_invoice',$this->search)
            ->orWhere('manual_invoice', $this->search)->latest()->first(),
        ]);
    }
}
