<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Agentprice;
use App\Models\Servicetype;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentpriceResource\Pages;
use App\Filament\Resources\AgentpriceResource\RelationManagers;

class AgentpriceResource extends Resource
{
    protected static ?string $model = Agentprice::class;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agent_id')
                    ->label('Agent Name')
                    ->options(Agent::where('agent_type','0')->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('servicetype_id')
                    ->label('Servicetype')
                    ->options(Servicetype::where('id',1)->pluck('description', 'id'))
                    ->searchable(),
                Select::make('boxtype_id')
                    ->relationship('boxtype', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->description} {$record->dimension}"),
                Select::make('zone_id')
                    ->label('Zone')
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->searchable(),

                Forms\Components\TextInput::make('price')
                    ->prefix('$')
                    ->numeric()
                    ->maxValue(42949672.95)
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent.full_name'),
                Tables\Columns\TextColumn::make('servicetype.description'),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('zone.description'),
                Tables\Columns\TextColumn::make('price')->money('USD'),
                Tables\Columns\TextColumn::make('note'),
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
            'index' => Pages\ListAgentprices::route('/'),
            'create' => Pages\CreateAgentprice::route('/create'),
            'edit' => Pages\EditAgentprice::route('/{record}/edit'),
        ];
    }
}
