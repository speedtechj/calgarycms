<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoxtypeResource\Pages;
use App\Filament\Resources\BoxtypeResource\RelationManagers;
use App\Models\Boxtype;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxtypeResource extends Resource
{
    protected static ?string $model = Boxtype::class;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dimension')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_box')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('dimension'),
                Tables\Columns\TextColumn::make('total_box'),
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
            'index' => Pages\ListBoxtypes::route('/'),
            'create' => Pages\CreateBoxtype::route('/create'),
            'edit' => Pages\EditBoxtype::route('/{record}/edit'),
        ];
    }    
}
