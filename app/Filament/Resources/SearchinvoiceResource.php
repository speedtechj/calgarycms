<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use Filament\Resources\Form;
use App\Models\Searchinvoice;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SearchinvoiceResource\Pages;
use App\Filament\Resources\SearchinvoiceResource\RelationManagers;
use App\Filament\Resources\SearchinvoiceResource\RelationManagers\InvattachRelationManager;
use App\Filament\Resources\SearchinvoiceResource\RelationManagers\RemarkstatusRelationManager;
use App\Filament\Resources\SearchinvoiceResource\RelationManagers\InvoicestatusRelationManager;


class SearchinvoiceResource extends Resource
{
    protected static ?string $model = Searchinvoice::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationLabel = 'Track Invoice';
    protected static ?string $label = 'Search Invoice';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sender Details')
                ->schema([
                    Forms\Components\TextInput::make('booking_invoice')
                    ->label('Booking Invoice')
                    ->dehydrated(false),
                Forms\Components\TextInput::make('manual_invoice')
                ->label('Manual Invoice')
                ->dehydrated(false),
                Forms\Components\TextInput::make('sendername')
                    ->label('Sender Name')
                    ->dehydrated(false)
                    ->helperText('Click the pencil icon to edit the sender details')
                    ->suffixAction(fn (?string $state): Action =>
                    Action::make('editsender')
                        ->icon('heroicon-o-pencil')
                        ->url(SenderResource::getUrl('edit', ['record' => (Sender::where('full_name',$state)->first()->id)])),
                      ),
                Forms\Components\TextInput::make('sender_address')
                ->label('Sender Address')
                ->dehydrated(false),
                Forms\Components\TextInput::make('senderprovince')
                ->label('Sender Province')
                ->dehydrated(false),
                Forms\Components\TextInput::make('sendercity')
                ->label('Sender City')
                ->dehydrated(false),
                Forms\Components\TextInput::make('senderpostalcode')
                ->label('Sender Postal Code')
                ->dehydrated(false),
                Forms\Components\TextInput::make('sendermobile_no')
                ->label('Sender Mobile Number')
                ->dehydrated(false),
                Forms\Components\TextInput::make('senderemail')
                ->label('Sender Email')
                ->dehydrated(false),
                ])->columns(3),
                Section::make('Receiver Details')
                ->schema([
                    Forms\Components\TextInput::make('receivername')
                    ->label('Receiver Name')
                    ->dehydrated(false),
                Forms\Components\TextInput::make('receiver_address')
                ->label('Receiver Address')
                ->dehydrated(false),
                Forms\Components\TextInput::make('receiver_barangay')
                ->label('Receiver Barangay')
                ->dehydrated(false),
                Forms\Components\TextInput::make('receiver_city')
                ->label('Receiver City')
                ->dehydrated(false),
                Forms\Components\TextInput::make('receiver_province')
                ->label('Reciever Province')
                ->dehydrated(false),
                Forms\Components\TextInput::make('receiver_mobileno')
                ->label('Receiver Mobile Number')
                ->dehydrated(false),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Generated Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('sender.mobile_no')
                ->label('Sender Number')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('receiver.mobile_no')
                ->label('Receiver Number')
                ->searchable(isIndividual: true, isGlobal: false),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            InvoicestatusRelationManager::class,
            RemarkstatusRelationManager::class,
            InvattachRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSearchinvoices::route('/'),
            // 'create' => Pages\CreateSearchinvoice::route('/create'),
            // 'edit' => Pages\EditSearchinvoice::route('/{record}/edit'),
            'view' => Pages\Viewsearchinvoice::route('/{record}'),
        ];
    }  
   
}
