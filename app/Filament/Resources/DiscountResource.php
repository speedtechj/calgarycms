<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Branch;
use App\Models\Boxtype;
use App\Models\Discount;
use App\Models\Servicetype;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DiscountResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DiscountResource\RelationManagers;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('servicetype_id')
                    ->label('Service Type')
                    ->options(Servicetype::all()->pluck('description', 'id'))
                    ->required(),
                Forms\Components\Select::make('zone_id')
                    ->label('Location')
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->required(),
                Forms\Components\Select::make('boxtype_id')
                    ->label('Box Type')
                    ->options(Boxtype::all()->pluck('description', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('discount_amount')
                    ->prefix('$')
                    ->required(),
                Forms\Components\Select::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::all()->pluck('business_name', 'id'))
                    ->required(),
                    Forms\Components\Toggle::make('is_active')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servicetype.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('zone.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('code')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->money('usd', shouldConvert: true)
                    ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is Active')
                    ->boolean()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('branch.business_name')
                    ->label('Branch')
                    ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Encoder')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    })
                    ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                ->sortable()
                ->toggleable(),
            ])
            ->filters([
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
