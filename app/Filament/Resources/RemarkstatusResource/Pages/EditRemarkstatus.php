<?php

namespace App\Filament\Resources\RemarkstatusResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\RemarkstatusResource;

class EditRemarkstatus extends EditRecord
{
    protected static string $resource = RemarkstatusResource::class;

    protected function getActions(): array
    {
        return [
          
        ];
    }
    protected function afterSave(): void
    {

        if ($this->record->status == 'Closed') {

            $this->record->update([
                'is_resolved' => true,
            ]);
            $recipients = $this->record->assign_by;
            Notification::make()
                ->title($this->record->statuscategory->description . ' ' . 'Request Closed & Resolved')
                ->body('Resolved by' .' '.auth()->user()->full_name)
                ->icon('heroicon-o-information-circle')
                ->iconColor('success')
                ->actions([
                    Action::make('View Reply')
                        ->url($this->getResource()::getUrl('edit', ['record' => $this->record]))
                ])
                ->sendToDatabase(User::where('id', $recipients)->first());
        }
    }
}
