<?php

namespace App\Filament\Resources\ReceiverResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use App\Models\Cityphil;
use App\Models\Receiver;
use App\Models\Provincephil;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SenderResource;
use App\Models\Receiveraddress;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ReceiveraddressRelationManager extends RelationManager
{
    protected static string $relationship = 'Receiveraddress';

    protected static ?string $recordTitleAttribute = 'address';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('provincephil_id')
                    ->relationship('provincephil', 'name')
                    ->label('Province')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(Provincephil::all()->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('cityphil_id', null)),
                    Forms\Components\Select::make('cityphil_id')
                    ->label('City')
                    ->relationship('cityphil', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {
                       $province = Provincephil::find($get('provincephil_id'));

                        if (!$province) {
                            // return $province->cityphil->pluck('city', 'id');
                            return null;
                        }
                        return $province->cityphil->pluck('name', 'id');

                    }),
                Forms\Components\Select::make('barangayphil_id')
                    ->label('Barangay')
                    ->required()
                    ->searchable()
                    ->preload()
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
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receiver.sender.full_name')
                ->url(fn (Receiveraddress $record) => SenderResource::getUrl('edit', ['record' => $record->receiver->sender])),
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincephil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('cityphil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('barangayphil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('loczone')
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
                    $zone = Cityphil::find($data['cityphil_id']);
                    // $data['user_id'] = auth()->id();
                    // $data['branch_id'] = 1;
                    $data['loczone'] = $zone->zone_id;
                   
                    return $data;
                }),
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
