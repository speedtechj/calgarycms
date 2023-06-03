<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cityphil;
use App\Models\Provincephil;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Receiveraddress;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReceiveraddressResource\Pages;
use App\Filament\Resources\ReceiveraddressResource\RelationManagers;

class ReceiveraddressResource extends Resource
{
    protected static ?string $navigationGroup = 'Customer';
    protected static ?string $navigationLabel = 'Receiver Address';
    protected static bool $shouldRegisterNavigation = false;

    public static ?string $label = 'Receiver Address';
    protected static ?string $model = Receiveraddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('receiver_id')
                ->relationship('receiver', 'full_name')
                // ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                ->label('Receiver Name')
                ->placeholder('Select Name')
                ->searchable()
                ->preload()
                ->required(),
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

                Tables\Columns\TextColumn::make('receiver.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
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
            'index' => Pages\ListReceiveraddresses::route('/'),
            'create' => Pages\CreateReceiveraddress::route('/create'),
            'edit' => Pages\EditReceiveraddress::route('/{record}/edit'),
        ];
    }
}
