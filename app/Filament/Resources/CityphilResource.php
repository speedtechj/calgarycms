<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Cityphil;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CityphilResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CityphilResource\RelationManagers;
use App\Filament\Resources\CityResource\RelationManagers\BarangayRelationManager;
use App\Filament\Resources\CityphilResource\RelationManagers\BarangayphilRelationManager;

class CityphilResource extends Resource
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Philippines City';
    public static ?string $label = 'Philippines Citys/Towns';
    protected static ?string $model = Cityphil::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('provincephil_id')
                ->relationship('Provincephil', 'name')->label('Philippines Province'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Select::make('zone_id')
                    ->label('Zone')
                    ->options(Zone::all()->pluck('name', 'id'))
                    ->searchable()
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('City/Town Name')
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
           BarangayphilRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCityphils::route('/'),
            'create' => Pages\CreateCityphil::route('/create'),
            'edit' => Pages\EditCityphil::route('/{record}/edit'),
        ];
    }
}
