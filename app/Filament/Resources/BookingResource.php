<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Receiver;
use App\Models\Servicetype;
use Filament\Resources\Form;
use App\Models\Senderaddress;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\BookingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\RelationManagers;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationGroup = 'Customer';
    protected static bool $shouldRegisterNavigation = false;


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                    Section::make('Customer Informaton')
                    ->schema([
                        Forms\Components\Select::make('sender_id')
                        ->label('Sender Name')
                        ->relationship('sender','first_name')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                        ->searchable()
                        ->reactive(),
                    Forms\Components\Select::make('senderaddress_id')
                        ->label('Sender Address')
                        ->options(function(callable $get){
                                $sender = Senderaddress::find($get('sender_id'));
                                if($sender){
                                    return Senderaddress::all()->pluck('address',$sender)->toArray();
                                }
     
                        }),
                        Forms\Components\Select::make('receiver_id')
                        ->label('Receiver Name')
                        ->label('Sender Name')
                        ->relationship('receiver','first_name')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                        ->searchable()
                        ->reactive(),
                    Forms\Components\TextInput::make('receiveraddress_id')
                    ->label('Receiver Address')
                        ->required(),
                    ])->columns(2),
                    Section::make('Booking  Information')
                    ->schema([
                        Forms\Components\Select::make('agent_id')
                        ->label('Agent Name')
                        ->relationship('agent', 'id')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                        ->searchable()
                        ->preload()
                    ->required(),
                    Forms\Components\DatePicker::make('pickup_date')
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->required(),
                    ])->columns(2),
                 Card::make()
                    ->schema([
                        Repeater::make('Booking')
                    ->schema([
                        Select::make('service_id')
                        ->label('Service Type')
                        ->options(Servicetype::all()->pluck('description', 'id'))
                        ->required(),
                        Select::make('boxtype_id')
                        ->searchable()
                        ->preload()
                        ->relationship('boxtype', 'id')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->description} {$record->dimension}"),
                    Forms\Components\TextInput::make('manual_invoice')
                        ->maxLength(255),

                    Forms\Components\Select::make('discount_id')
                    ->relationship('discount', 'code')
                        ->required(),
                    Forms\Components\TextInput::make('total_price')
                        ->required()
                        ->disabled(),
                        Toggle::make('is_admin')->inline(false)->label('Box Replacement'),
                        MarkdownEditor::make('content')
                        ->columnSpan('full')
                    ])
                    ->collapsible()
                    ->createItemButtonLabel('Add Items')
                    ->columns(2)
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender_id'),
                Tables\Columns\TextColumn::make('senderaddress_id'),
                Tables\Columns\TextColumn::make('receiver_id'),
                Tables\Columns\TextColumn::make('receiveraddress_id'),
                Tables\Columns\TextColumn::make('boxtype_id'),
                Tables\Columns\TextColumn::make('servicetype_id'),
                Tables\Columns\TextColumn::make('agent_id'),
                Tables\Columns\TextColumn::make('zone_id'),
                Tables\Columns\TextColumn::make('branch_id'),
                Tables\Columns\TextColumn::make('batch_id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('booking_invoice'),
                Tables\Columns\TextColumn::make('manual_invoice'),
                Tables\Columns\TextColumn::make('pickup_date')
                    ->date(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('discount_id'),
                Tables\Columns\TextColumn::make('total_price'),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
