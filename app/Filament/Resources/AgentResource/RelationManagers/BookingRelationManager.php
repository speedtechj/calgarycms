<?php

namespace App\Filament\Resources\AgentResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Packinglist;
use App\Models\Paymenttype;
use App\Models\Packlistitem;
use Filament\Resources\Form;
use App\Models\Bookingrefund;
use Filament\Resources\Table;
use App\Exports\Bookingexport;
use App\Models\Bookingpayment;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SenderResource;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class BookingRelationManager extends RelationManager
{
    protected static string $relationship = 'booking';

    protected static ?string $recordTitleAttribute = 'booking_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')->label('Sender')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Booking $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
                Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->color(static function ($state): string {
                        if ($state === 'Pickup') {
                            return 'success';
                        }

                        return 'info';
                    }),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('batch.id')
                    ->label('Batch Number')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno . " " . $record->batch->batch_year;
                    }),
                Tables\Columns\IconColumn::make('is_pickup')
                    ->label('Is Pickup')
                    ->boolean(),
                Tables\Columns\TextColumn::make('zone.description'),
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup'),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup Time')
                    ->getStateUsing(function (Model $record) {
                        return $record->start_time . " - " . $record->end_time;
                    }),
                Tables\Columns\TextColumn::make('dimension')->label('Dimension'),
                Tables\Columns\TextColumn::make('total_inches')->label('No. of Inches'),
                Tables\Columns\TextColumn::make('discount.discount_amount')->label('Discount'),
                Tables\Columns\TextColumn::make('total_price')->money('USD', shouldConvert: true),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_balance')->label('Balance')->money('USD', shouldConvert: true),
                Tables\Columns\TextColumn::make('refund_amount')->label('Refund'),
                Tables\Columns\TextColumn::make('agent.full_name')->label('Agent'),
                Tables\Columns\IconColumn::make('agent.agent_type')->label('In-House Agent')->boolean(),
            ])
            ->filters([
                Filter::make('is_paid')->query(fn (Builder $query): Builder => $query->where('is_paid', false))->default(),
                Filter::make('booking_date')
                    ->form([
                        Forms\Components\DatePicker::make('pickup_from'),
                        Forms\Components\DatePicker::make('pickup_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['pickup_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['pickup_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                // 
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\Action::make('print')->label('Print Invoice')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->url(fn (Booking $record) => route('barcode.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('barcode')->label('Print Barcode')
                        ->icon('heroicon-o-qrcode')
                        ->color('danger')
                        ->url(fn (Booking $record) => route('barcode1.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Payment')->label('Received Payment')
                        ->color('warning')
                        ->icon('heroicon-o-currency-dollar')
                        ->hidden(fn (Booking $record) => $record->is_paid == 1)
                        ->form([
                            Select::make('type_of_payment')
                                ->required()
                                ->label('Mode Of Payment')
                                ->options(Paymenttype::all()->pluck('name', 'id'))
                                ->searchable()
                                ->reactive(),
                            DatePicker::make('payment_date')->required(),
                            TextInput::make('reference_number')->label('Authorization Code/Reference Number/Cheque Number')
                                ->disabled(
                                    fn (Closure $get): bool => $get('type_of_payment') == 4
                                ),

                            TextInput::make('Amount')->label('Payment Amount')
                                ->required()
                                ->mask(
                                    fn (TextInput\Mask $mask) => $mask
                                        ->patternBlocks([
                                            'money' => fn (Mask $mask) => $mask
                                                ->numeric()
                                                ->thousandsSeparator(',')
                                                ->decimalSeparator('.'),
                                        ])
                                        ->pattern('$money'),
                                ),
                            TextInput::make('Booking_Balance')
                                ->label('Amount Due')
                                ->default(function (Booking $record) {
                                    return $record->payment_balance;
                                })
                                ->mask(
                                    fn (TextInput\Mask $mask) => $mask
                                        ->patternBlocks([
                                            'money' => fn (Mask $mask) => $mask
                                                ->numeric()
                                                ->minValue(1) // Set the minimum value that the number can be.
                                                ->maxValue(10000) // Set the maximum value that the number can be.
                                                ->thousandsSeparator(',')
                                                ->decimalSeparator('.'),
                                        ])
                                        ->pattern('$money'),
                                )
                                ->disabled(),
                        ])->action(function (Booking $record, array $data, $action) {

                            if ($record['payment_balance'] != 0) {
                                Bookingpayment::create([
                                    'booking_id' => $record->id,
                                    'paymenttype_id' => $data['type_of_payment'],
                                    'payment_date' => $data['payment_date'],
                                    'reference_number' => $data['reference_number'],
                                    'booking_invoice' => $record['booking_invoice'],
                                    'payment_amount' => $data['Amount'],
                                    'user_id' => auth()->id(),
                                    'sender_id' => $record['sender_id'],
                                ]);
                                $current_balance =  $record['payment_balance'] - $data['Amount'];
                                if ($current_balance >=  0) {
                                    $record->update(['payment_balance' => $current_balance]);
                                    Filament::notify('success', 'Payment Successful');
                                } else {
                                    Filament::notify('danger', 'Amount Paid is greater than the balance');
                                }
                                $paid_is = $current_balance == 0 ? 1 : 0;
                                $record->update(['is_paid' => $paid_is]);
                            }
                        }),
                    Tables\Actions\Action::make('Refund')->label('Refund Payment')
                        ->color('warning')
                        ->icon('heroicon-o-currency-dollar')
                        ->hidden(fn (Booking $record) => $record->refund_amount == null)
                        ->form([
                            Select::make('type_of_payment')
                                ->required()
                                ->label('Payment Method')
                                ->options(Paymenttype::all()->pluck('name', 'id'))
                                ->searchable()
                                ->reactive(),
                            DatePicker::make('payment_date')->required(),
                            TextInput::make('reference_number')->label('Authorization Code/Reference Number')
                                ->disabled(
                                    fn (Closure $get): bool => $get('type_of_payment') == 4
                                ),

                            TextInput::make('Amount')->label('Refund Amount Amount')
                                ->required()
                                ->mask(
                                    fn (TextInput\Mask $mask) => $mask
                                        ->patternBlocks([
                                            'money' => fn (Mask $mask) => $mask
                                                ->numeric()
                                                ->thousandsSeparator(',')
                                                ->decimalSeparator('.'),
                                        ])
                                        ->pattern('$money'),
                                ),
                            TextInput::make('Booking_Balance')
                                ->label('Refunded Amount')
                                ->default(function (Booking $record) {
                                    return $record->refund_amount;
                                })
                                ->mask(
                                    fn (TextInput\Mask $mask) => $mask
                                        ->patternBlocks([
                                            'money' => fn (Mask $mask) => $mask
                                                ->numeric()
                                                ->minValue(1) // Set the minimum value that the number can be.
                                                ->maxValue(10000) // Set the maximum value that the number can be.
                                                ->thousandsSeparator(',')
                                                ->decimalSeparator('.'),
                                        ])
                                        ->pattern('$money'),
                                )
                                ->disabled(),
                        ])->action(function (Booking $record, array $data, $action) {

                            if ($record['refund_amount'] != 0) {

                                $refund_balance =  $record['refund_amount'] - $data['Amount'];
                                if ($refund_balance >=  0) {
                                    Bookingrefund::create([
                                        'booking_id' => $record->id,
                                        'paymenttype_id' => $data['type_of_payment'],
                                        'payment_date' => $data['payment_date'],
                                        'reference_number' => $data['reference_number'],
                                        'booking_invoice' => $record['booking_invoice'],
                                        'payment_amount' => $data['Amount'],
                                        'user_id' => auth()->id(),
                                        'sender_id' => $record['sender_id'],
                                    ]);
                                    $record->update(['refund_amount' => $refund_balance]);
                                    Filament::notify('success', 'Payment Successful');
                                } else {
                                    Filament::notify('danger', 'Amount for Refund is greater than the Refund Balance');
                                }
                            }
                        }),

                    Tables\Actions\Action::make('Packinglist')->label('Packinglist')
                        ->color('warning')
                        ->icon('heroicon-o-currency-dollar')
                        ->form([

                            Forms\Components\TextInput::make('quantity'),
                            Forms\Components\Select::make('packlistitem_id')
                                ->label('Premade Items')
                                ->options(Packlistitem::all()->pluck('itemname', 'id')),
                            Forms\Components\TextInput::make('description'),
                            Forms\Components\TextInput::make('price'),
                            FileUpload::make('packlist_doc')
                                ->label('Packing List')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('packinglist')
                                ->visibility('private')
                                ->enableOpen(),
                            FileUpload::make('waiver_doc')
                                ->label(' Waiver')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('waiver')
                                ->visibility('private')
                                ->enableOpen(),
                        ])->action(function (Booking $record, array $data, $action) {
                            Packinglist::create([
                                'booking_id' => $record->id,
                                'sender_id' => $record->sender_id,
                                'packlistitem_id' => $data['packlistitem_id'],
                                'description' => $data['description'],
                                'packlistdoc' => $data['packlist_doc'],
                                'waverdoc' => $data['waiver_doc'],
                                'price' => $data['price'],
                            ]);
                        }),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                    ->icon('heroicon-o-document-download')
                    ->action(fn (Collection $records) => (new Bookingexport($records))->download('booking.xlsx')),
            ]);
    }
}
