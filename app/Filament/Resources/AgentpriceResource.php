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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
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
                    ->options(Agent::where('agent_type', '0')->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('servicetype_id')
                    ->label('Servicetype')
                    ->options(Servicetype::where('id', 1)->pluck('description', 'id'))
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
                Tables\Columns\TextColumn::make('agent.full_name')
                    ->label('Agent Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetype.description')
                    ->label('Service Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->label('Box Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone.description')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')->money('USD')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('agent_id')->label('Agent Name')
                ->searchable()
                    ->relationship('agent', 'full_name', fn (Builder $query) => $query->where('agent_type', '0')),
                SelectFilter::make('zone_id')->relationship('zone', 'description')->label('Area'),
                SelectFilter::make('servicetype_id')->relationship('servicetype', 'description')->label('Service'),

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
