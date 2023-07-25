<?php

namespace App\Filament\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Citycan;
use App\Models\Provincecan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SenderaddressRelationManager extends RelationManager
{
    protected static string $relationship = 'Senderaddress';

    protected static ?string $recordTitleAttribute = 'address';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('provincecan_id')
                ->relationship('provincecan', 'name')
                ->label('Province')
                ->searchable()
                ->preload()
                ->required()
                ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('citycan_id', null)),
            Forms\Components\Select::make('citycan_id')
                ->label('City')
                ->relationship('citycan', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->options(function (callable $get) {
                    $province = Provincecan::find($get('provincecan_id'));
                    if (!$province) {
                        // return Citycan::all()->pluck('name', 'id');
                        return null;
                    }
                    return $province->citycan->pluck('name', 'id');
                }),
            Forms\Components\Select::make('quadrant')
            ->options([
                'NW' => 'North West',
                'SW' => 'South West',
                'NE' => 'North East',
                'SE' => 'South East',
            ]),
            Forms\Components\TextInput::make('postal_code')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                ->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('citycan.name')
                ->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('quadrant')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('postal_code')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    // $zone = Cityphil::find($data['cityphil_id']);
                    $data['user_id'] = auth()->id();
                    // $data['branch_id'] = 1;
                    // $data['loczone'] = $zone->zone_id;
                   
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
