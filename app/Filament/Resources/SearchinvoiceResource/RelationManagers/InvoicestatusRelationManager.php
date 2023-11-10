<?php

namespace App\Filament\Resources\SearchinvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicestatusRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicestatus';
    public static ?string $title = 'View Invoice Status';
    protected static ?string $recordTitleAttribute = 'booking_invoice';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_invoice')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batach')
                ->label('Batch No')
                ->getStateUsing(fn ($record) => $record->batch->batchno . ' - ' . $record->batch->batch_year),
                Tables\Columns\TextColumn::make('trackstatus.description')
                ->label('Invoice Status'),
                Tables\Columns\TextColumn::make('date_update')
                ->label('Status Date'),
                Tables\Columns\TextColumn::make('location')
                ->label('Location'),
                Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill'),
                Tables\Columns\TextColumn::make('remarks')
                ->label('Remarks'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
               
            ])
            ->actions([
               
            ])
            ->bulkActions([
               
            ]);
    }    
}
