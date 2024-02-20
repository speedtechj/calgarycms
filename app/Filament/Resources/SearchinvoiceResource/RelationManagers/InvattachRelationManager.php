<?php

namespace App\Filament\Resources\SearchinvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvattachRelationManager extends RelationManager
{
    protected static string $relationship = 'invattach';
    public static ?string $title = 'Delivery Attachements';
    protected static ?string $recordTitleAttribute = 'booking_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('invattachment')
                ->label('Delivery Attachements')
                ->multiple()
                ->maxSize(30000)
                ->enableDownload()
                ->disk('public')
                ->directory('deliveryattachements')
                ->visibility('private')
                ->required()
                ->enableOpen(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_invoice')
                ->label('Booking Invoice'),
                Tables\Columns\TextColumn::make('booking.manual_invoice')
                ->label('Manual Invoice'),
                Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name'),
                Tables\Columns\TextColumn::make('user.full_name')
                ->label('Created By'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->since(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Create Delivery Attachements')
                    ->modalHeading('Delivery Attachements')
                    ->slideOver()
                    ->mutateFormDataUsing(function (RelationManager $livewire, array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['booking_id'] = $livewire->ownerRecord->id;
                        $data['sender_id'] = $livewire->ownerRecord->sender_id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
