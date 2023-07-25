<?php

namespace App\Filament\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Packlistitem;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PackinglistRelationManager extends RelationManager
{
    protected static string $relationship = 'packinglist';

    protected static ?string $recordTitleAttribute = 'booking_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity'),
                            Forms\Components\Select::make('packlistitem_id')
                                ->label('Premade Items')
                                ->options(Packlistitem::all()->pluck('itemname', 'id')),
                           
                            Forms\Components\TextInput::make('price'),
                            FileUpload::make('packlistdoc')
                                ->label('Packing List')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('packinglist')
                                ->visibility('private')
                                ->enableOpen(),
                            FileUpload::make('waverdoc')
                                ->label(' Waiver')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('waiver')
                                ->visibility('private')
                                ->enableOpen(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender'),
                Tables\Columns\TextColumn::make('booking.booking_invoice')
                ->label('Booking Invoice'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('packlistitem.itemname')->label('Premade item'),
                Tables\Columns\TextColumn::make('price')->label('Price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
