<?php

namespace App\Filament\Resources\RemarkstatusResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RemarkstatusResource;

class CreateRemarkstatus extends CreateRecord
{
    protected static string $resource = RemarkstatusResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       
        $data['user_id'] = auth()->id();
        $data['assign_by'] = auth()->id();
       
    //    if($data['manual_invoice'] == null){
    //        $data['manual_invoice'] = $data['booking_id'];
    //    }
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        $recipients = $this->record->assign_to;
        
  
       Notification::make()
       ->title('New Request Created')
       ->body($this->record->statuscategory->description. ' </br> '. 
       'Created by'.' '. $this->record->user->first_name . ' ' . $this->record->user->last_name)
       ->icon('heroicon-o-information-circle')
       ->iconColor('success')
       ->actions([
            Action::make('Reply')
            ->url($this->getResource()::getUrl('edit', ['record' => $this->record]))
       ])
       ->sendToDatabase(User::where('id', $recipients)->first());
    }

}
