<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('changePassword')
            ->label('Change Password')
            ->form([
                TextInput::make('new_password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->rule(Password::default()),
                    TextInput::make('new_password_confirmation')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->same('new_password')
                    ->rule(Password::default()),
            ])->action(function(array $data){
                $this->record->update([
                    'password' => Hash::make($data['new_password']),
                ]);
                $this->notify('success', 'Password Changed Successfully');
            }),
        ];
    }
}
