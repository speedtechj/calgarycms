<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BatchResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BatchResource\RelationManagers;

class BatchResource extends Resource
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $model = Batch::class;


    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('batchno')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('batch_year')
                    ->required()
                    ->default(now()->year),
                Toggle::make('is_active'),
                Forms\Components\MarkdownEditor::make('note')
                    ->maxLength(65535)->columnSpan('full'),
                    Toggle::make('is_lock'),
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batchno'),
                Tables\Columns\TextColumn::make('batch_year'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('note'),
                Tables\Columns\TextColumn::make('user_id')
                ->label('Encoder')
                ->getStateUsing(function(Model $record) {
                    return $record->user->first_name ." " .$record->user->last_name;
                }),
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
