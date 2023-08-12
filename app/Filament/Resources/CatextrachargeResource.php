<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatextrachargeResource\Pages;
use App\Filament\Resources\CatextrachargeResource\RelationManagers;
use App\Models\Catextracharge;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CatextrachargeResource extends Resource
{
    protected static ?string $model = Catextracharge::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Extra Category';
    public static ?string $label = 'Extra Charges Category';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            'index' => Pages\ListCatextracharges::route('/'),
            'create' => Pages\CreateCatextracharge::route('/create'),
            'edit' => Pages\EditCatextracharge::route('/{record}/edit'),
        ];
    }    
}
