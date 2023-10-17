<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Statuscategory;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StatuscategoryResource\Pages;
use App\Filament\Resources\StatuscategoryResource\RelationManagers;

class StatuscategoryResource extends Resource
{
    protected static ?string $model = Statuscategory::class;
    protected static ?string $navigationGroup = 'Request/Complain';
    protected static ?string $navigationLabel = 'Status Category';
    public static ?string $label = 'Request Status Category';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->label('Location')
                    ->relationship('branch', 'business_name')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.business_name')
                ->label('Location'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                    Tables\Columns\TextColumn::make('user_id')
                    ->label('Encoder')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    })
                    ->searchable()
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
            'index' => Pages\ListStatuscategories::route('/'),
            'create' => Pages\CreateStatuscategory::route('/create'),
            'edit' => Pages\EditStatuscategory::route('/{record}/edit'),
        ];
    }    
}
