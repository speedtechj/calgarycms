<?php

namespace App\Filament\Resources\SenderResource\RelationManagers;

use Closure;
use Wizard\Step;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Agentdiscount;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\Boxtype;
use App\Models\Citycan;
use App\Models\Discount;
use App\Models\Receiver;
use App\Models\Zoneprice;
use App\Models\Packinglist;
use App\Models\Paymenttype;
use App\Models\Servicetype;
use App\Models\Packlistitem;
use Filament\Resources\Form;
use App\Models\Bookingrefund;
use App\Models\Senderaddress;
use Filament\Resources\Table;
use App\Models\Bookingpayment;
use Filament\Facades\Filament;
use App\Models\Receiveraddress;
use App\Policies\BoxtypePolicy;
use Psy\VersionUpdater\SelfUpdate;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use App\Policies\PacklistitemPolicy;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Filament\Resources\RelationManagers\RelationManager;

class BookingRelationManager extends RelationManager
{
    protected static string $relationship = 'booking';
    // protected static ?string $navigationLabel = 'Receiver';
    // public static ?string $label = 'Receiver';
    protected static ?string $recordTitleAttribute = 'sender_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Customer')
                        ->schema([
                            Card::make()->schema([
                                Forms\Components\Select::make('senderaddress_id')
                                    ->label('Sender Address')
                                    ->options(function (RelationManager $livewire): array {
                                        return $livewire->ownerRecord->Senderaddress()
                                            ->pluck('address', 'id')
                                            ->toArray();
                                    })->required(),
                                Forms\Components\Select::make('receiver_id')
                                    ->label('Receiver Name')
                                    ->options(function (RelationManager $livewire): array {
                                        return $livewire->ownerRecord->receiver()
                                            ->pluck('full_name', 'id')
                                            ->toArray();
                                    })
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(fn (callable $set) => $set('receiveraddress_id', null)),
                                Forms\Components\Select::make('receiveraddress_id')
                                    ->label('Receiver Address')
                                    ->relationship('receiveraddress', 'id')
                                    ->required()
                                    ->reactive()
                                    ->options(function (callable $get) {
                                        $receiver = Receiver::find($get('receiver_id'));
                                        if (!$receiver) {
                                            // return Citycan::all()->pluck('name', 'id');
                                            return null;
                                        }
                                        return  $receiver->receiveraddress->pluck('address', 'id');
                                    })
                                    ->afterStateUpdated(function (Booking $booking, callable $set, callable $get, $state) {
                                        $loczone = Receiveraddress::find($state);
                                        $service_id = $get('servicetype_id');
                                        $zone_id = $loczone->loczone;
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        }
                                    })


                            ])->columns(2)
                        ]),
                    Wizard\Step::make('Schedule')
                        ->schema([
                            Card::make()->schema([
                                Select::make('servicetype_id')
                                    ->label('Service Type')
                                    ->options(Servicetype::all()->pluck('description', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $service_id = $get('servicetype_id');
                                        $zone_id = $loczone->loczone;
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($state == 1) {
                                            if ($agentid != null) {
                                                $agent_id = Agent::find($get('agent_id'));
                                                $agent_type = $agent_id->agent_type;
                                                if ($agent_type == 0 || $agent_type == null) {
                                                    $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                                } else {
                                                    $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                                }
                                                $set('total_price', $price);
                                            }
                                        } else {


                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                            $set('start_time', null);
                                            $set('end_time', null);
                                            $set('agent_id', null);
                                            $set('discount_id', null);
                                        }
                                    })
                                    ->reactive(),

                                Forms\Components\Select::make('agent_id')
                                    ->label('Agent Name')
                                    ->relationship('agent', 'id')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                                    ->searchable()
                                    ->reactive()
                                    ->preload()
                                    ->required()
                                    ->hidden(fn (\Closure $get) => $get('servicetype_id') == '2')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $service_id = $get('servicetype_id');
                                        $zone_id = $loczone->loczone;
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $state;
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($state);

                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                                $set('total_price', $price);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                                $set('total_price', $price);
                                            }
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    })
                                    ->dehydrated(false),
                                Hidden::make('agent_id')->disabled(),
                                Forms\Components\DatePicker::make('booking_date')
                                    ->required(),
                                Forms\Components\TimePicker::make('start_time')
                                    ->dehydrated(false)
                                    ->withoutSeconds()
                                    ->required()
                                    ->hidden(fn (\Closure $get) => $get('servicetype_id') == '2')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {

                                        $set('start_time', $state);
                                    }),
                                Hidden::make('start_time')->disabled(),
                                Forms\Components\TimePicker::make('end_time')
                                    ->dehydrated(false)
                                    ->withoutSeconds()
                                    ->required()
                                    ->hidden(fn (\Closure $get) => $get('servicetype_id') == '2')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $set('end_time', $state);
                                    }),
                                Hidden::make('end_time')->disabled(),
                                Select::make('batch_id')
                                    ->label('Batch')
                                    ->relationship('batch', 'id')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->batchno} {$record->batch_year}")
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Toggle::make('is_pickup')->label('Picked Up')
                                    ->hidden(fn (\Closure $get) => $get('servicetype_id') == '2'),
                                // ->hidden(fn(\Closure $get) => $get('servicetype_id') == '2'),
                            ])->columns(2)

                        ]),
                    Wizard\Step::make('Transaction')
                        ->schema([
                            Card::make()->schema([

                                Select::make('boxtype_id')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('boxtype', 'id')
                                    ->reactive()
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->description} {$record->dimension}")
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('irregular_length')
                                    ->suffix('inches')
                                    ->label('Length')
                                    ->numeric()
                                    ->minValue(19)
                                    ->maxValue(100)
                                    ->required()
                                    ->reactive()
                                    ->hidden(fn (\Closure $get) => $get('boxtype_id') !== '4')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {

                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('irregular_width')
                                    ->label('Width')
                                    ->suffix('inches')
                                    ->numeric()
                                    ->minValue(19)
                                    ->maxValue(100)
                                    ->required()
                                    ->reactive()
                                    ->hidden(fn (\Closure $get) => $get('boxtype_id') !== '4')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('irregular_height')
                                    ->label('Height')
                                    ->suffix('inches')
                                    ->numeric()
                                    ->minValue(34)
                                    ->maxValue(100)
                                    ->required()
                                    ->hidden(fn (\Closure $get) => $get('boxtype_id') !== '4')
                                    ->reactive()
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {

                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('total_inches')
                                    ->label('Total Inches')
                                    ->reactive()
                                    ->hidden(fn (\Closure $get) => $get('boxtype_id') !== '9')
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $agent_id = $get('agent_id');
                                        $agentid = $get('agent_id');
                                        $totalinches = $get('total_inches');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            $set('total_price', $price);
                                        } else {
                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            $set('total_price', $price);
                                        }
                                    }),
                                Forms\Components\TextInput::make('manual_invoice'),
                                Forms\Components\Select::make('discount_id')
                                    ->options(function (callable $get) {
                                        if ($get('boxtype_id') != null) {
                                            $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                            $zone_id = $loczone->loczone;
                                            $agent_id = Agent::find($get('agent_id'));
                                            
                                            if ($agent_id != null) {
                                                $agent_type = $agent_id->agent_type;
                                                if (!$agent_type) {
                                                                return Agentdiscount::where('zone_id', $zone_id)->where('is_active',true)->where('agent_id' ,$get('agent_id'))->where('servicetype_id', $get('servicetype_id'))->where('boxtype_id', $get('boxtype_id'))->get()->pluck('code', 'id');
                                                            } else {
                                                                return Discount::where('zone_id', $zone_id)->where('is_active',true)->where('servicetype_id', $get('servicetype_id'))->where('boxtype_id', $get('boxtype_id'))->get()->pluck('code', 'id');
                                                            }
                                            }else {
                                                return Discount::where('zone_id', $zone_id)->where('is_active',true)->where('servicetype_id', $get('servicetype_id'))->where('boxtype_id', $get('boxtype_id'))->get()->pluck('code', 'id');
                                            }
                                           
                                            
                                        }
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function (Booking $booking, Closure $set, Closure $get, $state) {
                                        $loczone = Receiveraddress::find($get('receiveraddress_id'));
                                        $zone_id = $loczone->loczone;
                                        $service_id = $get('servicetype_id');
                                        $boxtype_id = $get('boxtype_id');
                                        $discount = $get('discount_id');
                                        $length = $get('irregular_length');
                                        $width = $get('irregular_width');
                                        $height = $get('irregular_height');
                                        $totalinches = $get('total_inches');
                                        $agentid = $get('agent_id');
                                        if ($agentid != null) {
                                            $agent_id = Agent::find($get('agent_id'));
                                            $agent_type = $agent_id->agent_type;
                                            if ($agent_type == 0 || $agent_type == null) {
                                                $price = $booking->agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agentid, $totalinches);
                                            } else {
                                                $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            }
                                            if ($price < 0) {

                                                Filament::notify('danger', 'Price cannot be less than 0');
                                                $set('discount_id', null);
                                            } else {
                                                $set('total_price', $price);
                                            }
                                        } else {

                                            $price = $booking->calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches);
                                            // dump($price->price);
                                            if ($price < 0) {

                                                Filament::notify('danger', 'Price cannot be less than 0');
                                                $set('discount_id', null);
                                            } else {

                                                $set('total_price', $price);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('total_price')
                                    ->prefix('$')
                                    ->required()
                                    ->numeric()
                                    ->disabled(),
                                MarkdownEditor::make('note')->label('Notes')->columnSpan('full'),
                            ])->columns(2),
                        ]),

                ])->columnSpan('full')



            ]);
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
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date'),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup/Dropoff Time')
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
                Tables\Columns\TextColumn::make('notes')->label('Notes'),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('is_paid')->label('Is Paid')->query(fn (Builder $query): Builder => $query->where('is_paid', false))->default(),
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
                Tables\Actions\EditAction::make()
                    ->after(function (Booking $record, array $data) {
                        if ($record->boxtype_id != 9) {
                            $record->update([
                                'total_inches' => null,
                            ]);
                        }
                        if ($record->boxtype_id != 4) {
                            $record->update([
                                'irregular_width' => null,
                                'irregular_length' => null,
                                'irregular_height' => null,
                            ]);
                        };
                        if ($data['servicetype_id'] == 2) {
                            $record->update([
                                'agent_id' => null,

                            ]);
                        }
                        $booking_payment = Bookingpayment::where('booking_id', $record->id)->sum('payment_amount');
                        if ($record->is_paid != 0) {
                            if ($record->total_price > $booking_payment) {
                                $payment_balance = $record->total_price - $booking_payment;
                                $record->update([
                                    'payment_balance' => $payment_balance,
                                    'is_paid' => 0
                                ]);
                            } else {
                                $refund_sum = Bookingrefund::where('booking_id', $record->id)->sum('payment_amount');
                                if (!$refund_sum) {

                                    $record->update([
                                        'refund_amount' => $booking_payment - $record->total_price,

                                    ]);
                                } else {
                                    if ($record->total_price > $booking_payment) {
                                        $record->update([
                                            'refund_amount' => $booking_payment - $record->total_price - $refund_sum,

                                        ]);
                                    } else {
                                        $payment_balance =  $record->total_price + $refund_sum - $booking_payment;
                                        $record->update([
                                            'payment_balance' => $payment_balance,
                                            'is_paid' => 0
                                        ]);
                                    }
                                }
                            }
                        } else {
                            if ($record->total_price == $booking_payment) {
                                $record->update([
                                    'is_paid' => 1
                                ]);
                            }
                            $record->update([
                                'payment_balance' => $data['total_price'],
                            ]);
                        }
                        if ($record->total_price == 0) {
                            $record->update([
                                'is_paid' => 1
                            ]);
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
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
                Tables\Actions\DeleteBulkAction::make(),
            ])->reorderable('created_at');
    }
}
