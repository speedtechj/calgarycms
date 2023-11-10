<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Resources\SenderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SenderResource\RelationManagers;
use App\Filament\Resources\SenderResource\RelationManagers\BookingpaymentRelationManager;
use App\Filament\Resources\SenderResource\RelationManagers\BookingrefundRelationManager;
use App\Filament\Resources\SenderResource\RelationManagers\BookingRelationManager;
use App\Filament\Resources\SenderResource\RelationManagers\PackinglistRelationManager;
use App\Filament\Resources\SenderResource\RelationManagers\ReceiverRelationManager;
use App\Filament\Resources\SenderResource\RelationManagers\SenderaddressRelationManager;

class SenderResource extends Resource
{
    protected static ?string $navigationGroup = 'Customer';
    protected static ?string $navigationLabel = 'Sender';
    public static ?string $label = 'Sender';
    protected static ?string $model = Sender::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile_no')
                ->unique(ignorable: fn ($record) => $record)
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000)000-0000'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('home_no')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000)000-0000'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->unique(ignorable: fn ($record) => $record)
                    ->email()
                    ->required()
                    ->maxLength(255),
                MarkdownEditor::make('remark')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('mobile_no')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('home_no')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remark')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('branch.business_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')->label('Created By')
                   
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Edit'),
                    Tables\Actions\DeleteAction::make()->label('Delete'),
                ]),


            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingRelationManager::class,
            BookingpaymentRelationManager::class,
            // BookingrefundRelationManager::class,
            SenderaddressRelationManager::class,
            ReceiverRelationManager::class,
            PackinglistRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSenders::route('/'),
            'create' => Pages\CreateSender::route('/create'),
            'edit' => Pages\EditSender::route('/{record}/edit'),
        ];
    }
    protected function shouldPersistTableColumnSearchInSession(): bool
{
    return true;
}
protected function shouldPersistTableSearchInSession(): bool
{
    return true;
}
// protected function applySearchToTableQuery(Builder $query): Builder
// {
//     if (filled($searchQuery = $this->getTableSearchQuery())) {
//         $query->whereIn('id', Sender::search($searchQuery)->keys());
//     }
 
//     return $query;
// }

}
