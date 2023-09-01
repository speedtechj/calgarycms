<?php

namespace App\Filament\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use App\Models\Cityphil;
use App\Models\Receiver;
use App\Models\Provincephil;
use Filament\Resources\Form;
use App\Models\Senderaddress;
use Filament\Resources\Table;
use App\Models\Receiveraddress;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ReceiveraddressResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ReceiverRelationManager extends RelationManager
{
    protected static string $relationship = 'Receiver';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('mobile_no')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('+63(0000)000-0000'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('home_no')
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('+63(0000)000-0000'))
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                        MarkdownEditor::make('remark')
                        ->label('Note')
                        ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                ->searchable()
                ->toggleable()
                ->sortable()
                ->url(fn (Receiver $record) => route('filament.resources.receivers.edit', $record)),
                Tables\Columns\TextColumn::make('mobile_no')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('home_no')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('remark')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('newaddress')->label('New Address')
                ->color('success')
                ->icon('heroicon-o-location-marker')
                ->form([  
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('provincephil_id')
                    ->label('Province')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(Provincephil::all()->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('cityphil_id', null);
                        $set('barangayphil_id', null);
                    }),
                    Forms\Components\Select::make('cityphil_id')
                    ->label('City')
                    // ->relationship('cityphil', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        
                       $province = Provincephil::find($get('provincephil_id'));

                        if (!$province) {
                            // return $province->cityphil->pluck('city', 'id');
                            return null;
                        }
                        return $province->cityphil->pluck('name', 'id');

                    })
                    ->afterStateUpdated(function (callable $set) {
                        $set('barangayphil_id', null);
                    }),
                Forms\Components\Select::make('barangayphil_id')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->options(function (callable $get) {
                        $city = Cityphil::find($get('cityphil_id'));

                         if (!$city) {
                             // return $province->cityphil->pluck('city', 'id');
                             return null;
                         }
                         return $city->barangayphil->pluck('name', 'id');

                     }),
                Forms\Components\TextInput::make('zip_code')
                    ->maxLength(255),
                
                ])->action(function (Receiver $record, array $data) {
                        $zoneid = Cityphil::find($data['cityphil_id'])->zone_id;
                       Receiveraddress::create([
                            'receiver_id' => $record->id,
                            'address' => $data['address'],
                            'provincephil_id' => $data['provincephil_id'],
                            'cityphil_id' => $data['cityphil_id'],
                            'barangayphil_id' => $data['barangayphil_id'],
                            'zip_code' => $data['zip_code'],
                            'loczone' => $zoneid,
                            
                        ]);
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);

            
    }
    
}
