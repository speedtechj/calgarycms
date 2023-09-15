<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
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
use App\Exports\CollectionExport;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\BookingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\RelationManagers;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationLabel = 'Collection Report';
    public static ?string $label = 'Collection Report';
    protected static ?string $navigationGroup = 'Report';
    protected static bool $shouldRegisterNavigation = true;


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Customer Informaton')
                    ->schema([
                        Forms\Components\Select::make('sender_id')
                            ->label('Sender Name')
                            ->relationship('sender', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->reactive(),
                        Forms\Components\Select::make('senderaddress_id')
                            ->label('Sender Address')
                            ->options(function (callable $get) {
                                $sender = Senderaddress::find($get('sender_id'));
                                if ($sender) {
                                    return Senderaddress::all()->pluck('address', $sender)->toArray();
                                }
                            }),
                        Forms\Components\Select::make('receiver_id')
                            ->label('Receiver Name')
                            ->label('Sender Name')
                            ->relationship('receiver', 'first_name')
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
                Tables\Columns\TextColumn::make('booking_invoice'),
                Tables\Columns\TextColumn::make('manual_invoice'),
                Tables\Columns\TextColumn::make('sender.full_name'),
                Tables\Columns\TextColumn::make('senderaddress.address'),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('servicetype.description'),
                Tables\Columns\TextColumn::make('agent.full_name'),
                Tables\Columns\TextColumn::make('zone.description'),
                Tables\Columns\TextColumn::make('pickup_date')
                    ->date(),
                Tables\Columns\TextColumn::make('discount.discount_amount'),
                Tables\Columns\TextColumn::make('extracharge_amount'),
                Tables\Columns\TextColumn::make('total_price')->money('USD', shouldConvert: true),
                Tables\Columns\IconColumn::make('is_paid')
                ->label('Paid')
                ->boolean(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Payment Date'),


            ])->deferLoading()
            
            ->filters([
                Filter::make('is_paid')
                    ->query(fn (Builder $query): Builder => $query->where('is_paid', true)),
                    Filter::make('booking_date')->label('Booking Date')
                    ->form([
                        Forms\Components\DatePicker::make('book_from'),
                        Forms\Components\DatePicker::make('book_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['book_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['book_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    }),   
                    SelectFilter::make('servicetype_id')->relationship('servicetype', 'description')->label('Service Type'),
                    SelectFilter::make('agent_id')->relationship('agent', 'full_name')->label('Agent')->searchable(), 
                    Filter::make('payment_date')->label('Payment Date')
                    ->form([
                        Forms\Components\DatePicker::make('payment_from'),
                        Forms\Components\DatePicker::make('payment_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payment_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['payment_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    })
                    ->default(0)   
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                    ->icon('heroicon-o-document-download')
                    ->action(fn (Collection $records) => (new CollectionExport($records))->download('collection.xlsx')),
                // ->action(fn (Collection $records) => (new CollectionExport($records))->download('collection.xlsx')),
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
    protected function shouldPersistTableColumnSearchInSession(): bool
{
    return true;
}
protected function shouldPersistTableSearchInSession(): bool
{
    return true;
}
}
