<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Packinglist;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PackinglistResource\Pages;
use App\Filament\Resources\PackinglistResource\RelationManagers;

class PackinglistResource extends Resource
{
    protected static ?string $model = Packinglist::class;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\Select::make('invoice')
                    ->label('Invoice Number')
                    ->searchable(),
                Forms\Components\TextInput::make('quantity'),
                Forms\Components\Select::make('packlistitem_id')
                ->label('Premade Items')
                ->relationship('packlistitem', 'itemname')
                    ->required(),
                    Forms\Components\TextInput::make('Description'),
                    Forms\Components\TextInput::make('Item'),
                Forms\Components\TextInput::make('price')
                    ->required(),
                    FileUpload::make('file_doc')
                    ->label('Packing List')
                    ->multiple()
                    ->enableDownload()
                    ->disk('public')
                    ->directory('branch')
                    ->visibility('private')
                    ->enableOpen(),
                    FileUpload::make('file_doc')
                    ->label('
                    Waiver')
                    ->multiple()
                    ->enableDownload()
                    ->disk('public')
                    ->directory('branch')
                    ->visibility('private')
                    ->enableOpen(),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('packlistitem_id'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('invoice'),
                Tables\Columns\TextColumn::make('price'),
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
            'index' => Pages\ListPackinglists::route('/'),
            'create' => Pages\CreatePackinglist::route('/create'),
            'edit' => Pages\EditPackinglist::route('/{record}/edit'),
        ];
    }
}
