<?php

namespace App\Filament\Resources\AgentResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
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
use App\Models\Receiveraddress;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
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
                Tables\Columns\TextInputColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')->label('Sender')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
                Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno . " " . $record->batch->batch_year;
                    }),
                Tables\Columns\IconColumn::make('is_pickup')
                    ->label('Is Pickup')
                    ->boolean(),
                Tables\Columns\TextColumn::make('zone.description'),
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date')
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup/Dropoff Time')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(function (Model $record) {
                        return $record->start_time . " - " . $record->end_time;
                    }),
                Tables\Columns\TextColumn::make('dimension')->label('Dimension')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_inches')->label('No. of Inches')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount.discount_amount')->label('Discount')->money('USD', shouldConvert: true),
                Tables\Columns\TextColumn::make('agentdiscount.discount_amount')->label('Agent Discount')->money('USD', shouldConvert: true),
                Tables\Columns\TextColumn::make('total_price')->money('USD', shouldConvert: true),
                Tables\Columns\TextColumn::make('payment_date')->date()->label('Payment Date')->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_balance')->label('Balance')->money('USD', shouldConvert: true),
                Tables\Columns\TextColumn::make('refund_amount')->label('Refund')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent.full_name')->label('Agent')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('agent.agent_type')->label('In-House Agent')->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')->label('Notes'),

            ])->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('is_paid')->query(fn (Builder $query): Builder => $query->where('is_paid', false))->default(),
                Filter::make('booking_date')->label('Pickup Date')
                    ->form([
                        Section::make('Pickup Date')
                            ->schema([
                                Forms\Components\DatePicker::make('pickup_from'),
                                Forms\Components\DatePicker::make('pickup_until')
                            ])->collapsible(),

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
                    }),
                Filter::make('payment_date')->label('Payment Date')
                    ->form([
                        Section::make('Payment Date')
                            ->schema([
                                Forms\Components\DatePicker::make('payment_from'),
                                Forms\Components\DatePicker::make('payment_until'),
                            ])->collapsible()

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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {

                        $data['user_id'] = auth()->id();
                        $data['branch_id'] = 1;
                        $data['payment_balance'] = $data['total_price'];
                        $data['zone_id'] = Receiveraddress::find($data['receiveraddress_id'])->loczone;
                        return $data;
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                //     // ->beforeFormFilled(function (Booking $record, array $data) {
                //     //     dump($record->is_agent);
                //     // })
                //     ->after(function (Booking $record, array $data) {
                //         // dump($data); 
                //         if ($record->servicetype_id == 1) {
                //             if ($record->agent->agent_type == 0) {
                //                 $record->update([
                //                     'is_agent' => 1,

                //                 ]);
                //             } else {
                //                 $record->update([
                //                     'is_agent' => 0,

                //                 ]);
                //             }
                //         } else {

                //             $record->update([
                //                 'is_agent' => 0,

                //             ]);
                //         }
                //         if ($record->boxtype_id != 9) {
                //             $record->update([
                //                 'total_inches' => null,
                //             ]);
                //         }
                //         if ($record->boxtype_id != 4) {
                //             $record->update([
                //                 'irregular_width' => null,
                //                 'irregular_length' => null,
                //                 'irregular_height' => null,
                //             ]);
                //         };
                //         if ($data['servicetype_id'] == 2) {
                //             $record->update([
                //                 'agent_id' => null,

                //             ]);
                //         }

                //         if ($record->agent_id != null) {
                //             $agent = Agent::find($record->agent_id);
                //             $agentid = $agent->agent_type;
                //             if ($agentid == 0) {
                //                 if ($record->discount_id != null) {
                //                     $record->update([
                //                         'discount_id' => null,
                //                     ]);
                //                 }
                //             } else {
                //                 if ($record->agentdiscount_id != null) {
                //                     $record->update([
                //                         'agentdiscount_id' => null,
                //                     ]);
                //                 }
                //             }
                //         } else {
                //             if ($record->agentdiscount_id != null) {
                //                 $record->update([
                //                     'agentdiscount_id' => null,
                //                 ]);
                //             }
                //         }

                //         $booking_payment = Bookingpayment::where('booking_id', $record->id)->sum('payment_amount');
                //         if ($record->is_paid != 0) {
                //             if ($record->total_price > $booking_payment) {
                //                 $payment_balance = $record->total_price - $booking_payment;
                //                 $record->update([
                //                     'payment_balance' => $payment_balance,
                //                     'is_paid' => 0
                //                 ]);
                //             } else {
                //                 $refund_sum = Bookingrefund::where('booking_id', $record->id)->sum('payment_amount');
                //                 if (!$refund_sum) {

                //                     $record->update([
                //                         'refund_amount' => $booking_payment - $record->total_price,

                //                     ]);
                //                 } else {
                //                     if ($record->total_price > $booking_payment) {
                //                         $record->update([
                //                             'refund_amount' => $booking_payment - $record->total_price - $refund_sum,

                //                         ]);
                //                     } else {
                //                         $payment_balance =  $record->total_price + $refund_sum - $booking_payment;
                //                         $record->update([
                //                             'payment_balance' => $payment_balance,
                //                             'is_paid' => 0
                //                         ]);
                //                     }
                //                 }
                //             }
                //         } else {
                //             if ($record->total_price == $booking_payment) {
                //                 $record->update([
                //                     'is_paid' => 1
                //                 ]);
                //             }
                //             $record->update([
                //                 'payment_balance' => $data['total_price'],
                //             ]);
                //         }
                //         if ($record->total_price == 0) {
                //             $record->update([
                //                 'is_paid' => 1
                //             ]);
                //         }
                //     }),
                // Tables\Actions\DeleteAction::make(),
                ActionGroup::make([
                    Tables\Actions\Action::make('print')
                        ->label('Print Invoice')
                        ->color('warning')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Booking $record) => route('barcode.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('barcode')
                        ->color('success')
                        ->icon('heroicon-o-qrcode')
                        ->label('Print Barcode')
                        ->url(fn (Booking $record) => route('barcode1.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Payment')->label('Received Payment')
                        ->color('success')
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
                                $record->update(['payment_date' => $data['payment_date']]);
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
                            $packinglistcount = Packinglist::where('booking_id', $record->id)->count();

                            if ($packinglistcount < 3) {
                                Packinglist::create([
                                    'booking_id' => $record->id,
                                    'sender_id' => $record->sender_id,
                                    'quantity' => $data['quantity'],
                                    'packlistitem_id' => $data['packlistitem_id'],
                                    'packlistdoc' => $data['packlist_doc'],
                                    'waverdoc' => $data['waiver_doc'],
                                    'price' => $data['price'],
                                ]);
                                Filament::notify('success', 'Record Successfully save');
                            } else {
                                Filament::notify('danger', 'Limit Exceed need only 3 records');
                            }
                        }),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                    ->icon('heroicon-o-document-download')
                    ->action(fn (Collection $records) => (new Bookingexport($records))->download('booking.xlsx')),
                Tables\Actions\BulkAction::make('Received Payment')
                    ->label('Received Payment')
                    ->color('warning')
                    ->icon('heroicon-o-currency-dollar')
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


                    ])->action(function (Collection $records, array $data, $action) {
                        $records->each(function ($record) use ($data) {
                            if ($record->payment_balance != 0) {

                                Bookingpayment::create([
                                    'booking_id' => $record->id,
                                    'paymenttype_id' => $data['type_of_payment'],
                                    'payment_date' => $data['payment_date'],
                                    'reference_number' => $data['reference_number'],
                                    'booking_invoice' => $record->booking_invoice,
                                    'payment_amount' => $record->payment_balance,
                                    'user_id' => auth()->id(),
                                    'sender_id' => $record->sender_id,
                                ]);
                                $record->update(['payment_date' => $data['payment_date']]);
                                Booking::where('id', $record->id)->update([
                                    'payment_balance' => 0,
                                    'is_paid' => true,
                                ]);
                            }
                        });
                        Filament::notify('success', 'Payment Successfully received');
                    }),
                Tables\Actions\BulkAction::make('Update Pickup')
                    ->label('Pickup update')
                    ->icon('heroicon-o-clipboard-list')
                    ->color('warning')
                    ->action(function (Collection $records, array $data, $action) {
                        $records->each(function ($record) use ($data) {
                            if ($record->is_pickup == false) {
                                Booking::where('id', $record->id)->update([
                                    'is_pickup' => true,
                                ]);
                            }
                        });
                        Filament::notify('success', 'Pickup Successfully updated');
                    }),
            ])->reorderable('created_at');
    }
}
