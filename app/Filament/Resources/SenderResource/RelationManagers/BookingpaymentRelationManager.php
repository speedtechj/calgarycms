<?php

namespace App\Filament\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class BookingpaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingpayment';
    public static ?string $title = 'Received Payment';
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_id')
                    ->required(),
                Forms\Components\TextInput::make('sender_id')
                    ->required(),
                Forms\Components\TextInput::make('paymenttype_id')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\TextInput::make('auth_code')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice'),
                Tables\Columns\TextColumn::make('sender.full_name'),
                Tables\Columns\TextColumn::make('paymenttype.name'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date(),
                Tables\Columns\TextColumn::make('payment_amount')->money('USD',shouldConvert:true),
                Tables\Columns\TextColumn::make('reference_number'),
                Tables\Columns\TextColumn::make('user_id')->label('Created By')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable()
                ->getStateUsing(function(Model $record) {
                    return $record->user->first_name ." " .$record->user->last_name;
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
