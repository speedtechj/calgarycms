<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingpaymentResource\Pages;
use App\Filament\Resources\BookingpaymentResource\RelationManagers;
use App\Models\Bookingpayment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingpaymentResource extends Resource
{
    protected static ?string $model = Bookingpayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Settings';
    protected static bool $shouldRegisterNavigation = false;

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
                Tables\Columns\TextColumn::make('booking_id'),
                Tables\Columns\TextColumn::make('sender_id'),
                Tables\Columns\TextColumn::make('paymenttype_id'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date(),
                Tables\Columns\TextColumn::make('auth_code'),
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
            'index' => Pages\ListBookingpayments::route('/'),
            'create' => Pages\CreateBookingpayment::route('/create'),
            'edit' => Pages\EditBookingpayment::route('/{record}/edit'),
        ];
    }    
}
