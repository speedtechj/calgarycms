<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Provincecan;
use Filament\Resources\Form;
use App\Models\Senderaddress;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SenderaddressResource\Pages;
use App\Filament\Resources\SenderaddressResource\RelationManagers;

class SenderaddressResource extends Resource
{
    protected static ?string $navigationGroup = 'Customer';
    protected static ?string $navigationLabel = 'Sender Address';
    protected static bool $shouldRegisterNavigation = false;

    public static ?string $label = 'Sender Address';
    protected static ?string $model = Senderaddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sender_id')
                    ->relationship('sender', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                    ->label('Sender Name')
                    ->searchable()
                    ->preload()
                    ->required(),
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
                    ->options(self::$model::QUADRANT),
                Forms\Components\TextInput::make('postal_code')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender')
                ->getStateUsing(function(Model $record) {
                    return $record->sender->first_name ." " .$record->sender->last_name;
                })->label('Sender Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
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
            'index' => Pages\ListSenderaddresses::route('/'),
            'create' => Pages\CreateSenderaddress::route('/create'),
            'edit' => Pages\EditSenderaddress::route('/{record}/edit'),
        ];
    }
}
