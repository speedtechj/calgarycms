<?php

namespace App\Filament\Resources\SearchinvoiceResource\Pages;

use App\Filament\Resources\SearchinvoiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSearchinvoice extends ViewRecord
{
    protected static string $resource = SearchinvoiceResource::class;
    protected function beforeFill(): void
    {
        // sender info
        $this->form->getRecord()->sendername = $this->record->sender->full_name;
        $this->form->getRecord()->sender_address = $this->record->senderaddress->address;
        $this->form->getRecord()->senderprovince = $this->record->senderaddress->provincecan->name;
        $this->form->getRecord()->sendercity = $this->record->senderaddress->citycan->name;
        $this->form->getRecord()->senderpostalcode = $this->record->senderaddress->postal_code;
        $this->form->getRecord()->sendermobile_no = $this->record->sender->mobile_no;
        $this->form->getRecord()->senderemail = $this->record->sender->email;
        // receiver info
        $this->form->getRecord()->receivername = $this->record->receiver->full_name;
        $this->form->getRecord()->receiver_address = $this->record->receiveraddress->address;
        $this->form->getRecord()->receiver_barangay = $this->record->receiveraddress->barangayphil->name ?? '';
        $this->form->getRecord()->receiver_city = $this->record->receiveraddress->cityphil->name;
        $this->form->getRecord()->receiver_province = $this->record->receiveraddress->provincephil->name;
        $this->form->getRecord()->receiver_mobileno = $this->record->receiver->mobile_no;
       
    }
}
