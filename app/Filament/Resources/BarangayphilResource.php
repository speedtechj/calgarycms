<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barangayphil;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangayphilResource\Pages;
use App\Filament\Resources\BarangayphilResource\RelationManagers;

class BarangayphilResource extends Resource
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Philippines Barangay';
    public static ?string $label = 'Philippines Barangay';
    protected static ?string $model = Barangayphil::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cityphil_id')
                ->relationship('Cityphil', 'name')->label('Philippines City'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cityphil.name')->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Barangay Name')
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
            'index' => Pages\ListBarangayphils::route('/'),
            'create' => Pages\CreateBarangayphil::route('/create'),
            'edit' => Pages\EditBarangayphil::route('/{record}/edit'),
        ];
    }
}
