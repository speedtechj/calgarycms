<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvincephilResource\Pages;
use App\Filament\Resources\ProvincephilResource\RelationManagers;
use App\Filament\Resources\ProvincephilResource\RelationManagers\CityphilRelationManager;
use App\Models\Provincephil;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProvincephilResource extends Resource
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Philippines Province';
    public static ?string $label = 'Philippines Province';
    protected static ?string $model = Provincephil::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Province Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            CityphilRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvincephils::route('/'),
            'create' => Pages\CreateProvincephil::route('/create'),
            'edit' => Pages\EditProvincephil::route('/{record}/edit'),
        ];
    }
}
