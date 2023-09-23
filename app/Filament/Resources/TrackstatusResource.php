<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Branch;
use App\Models\Trackstatus;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TrackstatusResource\Pages;
use App\Filament\Resources\TrackstatusResource\RelationManagers;

class TrackstatusResource extends Resource
{
    protected static ?string $model = Trackstatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Invoice Status';
    public static ?string $label = 'Invoice Status';
    protected static ?string $navigationGroup = 'Invoice Status';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('Status Description')
                    ->required()
                    ->maxLength(191),
                    Forms\Components\Select::make('branch_id')
                    ->label('Location')
                    ->options(Branch::all()->pluck('business_name', 'id'))
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branch.business_name'),
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
            'index' => Pages\ListTrackstatuses::route('/'),
            'create' => Pages\CreateTrackstatus::route('/create'),
            'edit' => Pages\EditTrackstatus::route('/{record}/edit'),
        ];
    }    
}
