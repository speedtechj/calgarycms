<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingrefundResource\Pages;
use App\Filament\Resources\BookingrefundResource\RelationManagers;
use App\Models\Bookingrefund;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingrefundResource extends Resource
{
    protected static ?string $model = Bookingrefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('payment_amount')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\TextInput::make('booking_invoice')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cheque_number')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_id'),
                Tables\Columns\TextColumn::make('sender_id'),
                Tables\Columns\TextColumn::make('paymenttype_id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('payment_amount'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date(),
                Tables\Columns\TextColumn::make('booking_invoice'),
                Tables\Columns\TextColumn::make('reference_number'),
                Tables\Columns\TextColumn::make('cheque_number'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingrefunds::route('/'),
            'create' => Pages\CreateBookingrefund::route('/create'),
            'edit' => Pages\EditBookingrefund::route('/{record}/edit'),
        ];
    }    
}
