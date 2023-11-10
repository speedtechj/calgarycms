<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Booking;
use App\Models\Remarkstatus;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Statuscategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RemarkstatusResource\Pages;
use App\Filament\Resources\RemarkstatusResource\RelationManagers;

class RemarkstatusResource extends Resource
{
    protected static ?string $model = Remarkstatus::class;
    protected static ?string $navigationGroup = 'Request/Complain';
    protected static ?string $navigationLabel = 'Reques Status Info ';
    public static ?string $label = 'Request Information';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Group::make()
                ->schema([
                    Section::make('Search Booking')
                        ->schema([
                            Forms\Components\Select::make('booking_id')
                                ->relationship('booking', 'booking_invoice')
                                ->disabledOn('edit')
                                ->searchable()
                                ->label('Generated Invoice')
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                    $bookinginfo = Booking::where('id', $state)->first();
                                    $set('sender',$bookinginfo->sender->full_name ?? '');
                                    $set('receiver',$bookinginfo->receiver->full_name ?? '');
                                    $set('senderaddress',$bookinginfo->receiveraddress->address ?? '');
                                    $set('manual_invoice', $state);
                                    
                                }),
                                Forms\Components\Select::make('manual_invoice')
                                ->disabledOn('edit')
                                ->relationship('booking', 'manual_invoice')
                                ->searchable()
                                ->reactive()
                                ->label('Manual Invoice')
                               ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                $bookinginfo = Booking::where('id', $state)->first();
                                $set('sender',$bookinginfo->sender->full_name ?? '');
                                $set('receiver',$bookinginfo->receiver->full_name ?? '');
                                $set('booking_id', $state);
                                }),
                            
                        ])->columns(2),
                    Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('sender')
                            ->label('Sender Name')
                            ->dehydrated(false),
                            Forms\Components\TextInput::make('receiver')
                            ->label('Receiver Name')
                            ->dehydrated(false),
                            Forms\Components\TextInput::make('senderaddress')
                            ->label('Sender Address')
                            ->dehydrated(false),
                            Forms\Components\TextInput::make('receiveraddress')
                            ->label('Receiver Address')
                            ->dehydrated(false)
                    ])->columns(2),
                    Section::make('Request Information')
                        ->schema([
                            Forms\Components\Select::make('statuscategory_id')
                                ->label('Title')
                                ->options(Statuscategory::all()->pluck('description', 'id'))
                                // ->options(Statuscategory::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                                ->required()
                                ->disabledOn('edit'),
                            Forms\Components\Select::make('assign_to')
                                ->options(User::where('branch_id', '!=', auth()->user()->branch_id)->pluck('full_name', 'id'))
                                ->required()
                                ->hiddenOn('edit'),
                            Forms\Components\Select::make('status')
                                ->options(self::$model::STATUS)
                                ->required(),


                        ])

                ]),
            Group::make()
                ->schema([
                    Section::make('Document Attachements')
                        ->schema([

                            Forms\Components\MarkdownEditor::make('sender_comment')
                                ->label('Sender Remarks/Comments')
                                ->maxLength(65535)
                                ->disabledOn('edit'),
                                Forms\Components\MarkdownEditor::make('receiver_comment')
                                ->label('Receiver Remarks/Comments')
                                ->maxLength(65535)
                                ->disabledOn('create'),
                           
                            FileUpload::make('invoicedoc')
                                ->label('Document Attachements')
                                ->multiple()
                                ->maxSize(30000)
                                ->enableDownload()
                                ->disk('public')
                                ->directory('remarkstatus')
                                ->visibility('private')
                                ->enableOpen(),

                        ])

                ])

        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                ->label('Ticket Number')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('booking.booking_invoice')
                ->label('Generated Invoice')
                ->searchable(),
                Tables\Columns\TextColumn::make('booking.manual_invoice')
                ->label('Manual Invoice')
                ->searchable(),
                Tables\Columns\TextColumn::make('statuscategory.description')->label('Task Title'),
                Tables\Columns\TextColumn::make('assignby.full_name')
                ->label('Assigned By'),
                Tables\Columns\TextColumn::make('assignto.full_name')
                ->label('Assigned To'),
                    
                Tables\Columns\IconColumn::make('is_resolved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('sender_comment'),
                Tables\Columns\TextColumn::make('receiver_comment'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRemarkstatuses::route('/'),
            'create' => Pages\CreateRemarkstatus::route('/create'),
            'edit' => Pages\EditRemarkstatus::route('/{record}/edit'),
        ];
    }    
}
