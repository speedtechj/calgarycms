<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyinfoResource\Pages;
use App\Filament\Resources\CompanyinfoResource\RelationManagers;
use App\Models\Companyinfo;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyinfoResource extends Resource
{
    protected static ?string $model = Companyinfo::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_address')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_phone')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_website')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_tracking')
                    ->required()
                    ->maxLength(191),
                    Forms\Components\FileUpload::make('company_logo')
                    ->label('Company Logo')
                    ->preserveFilenames()
                    ->enableDownload()
                    ->disk('public')
                    ->directory('logo')
                    ->visibility('private')
                    ->enableOpen(),
                    Forms\Components\TextInput::make('company_slogan')
                    ->required()
                    ->maxLength(191),
                    Forms\Components\TextInput::make('barcode_label')
                    ->required()
                    ->maxLength(191),
                    Forms\Components\TextInput::make('etransfer_email')
                    ->required()
                    ->email()
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name'),
                Tables\Columns\TextColumn::make('company_address'),
                Tables\Columns\TextColumn::make('company_phone'),
                Tables\Columns\TextColumn::make('company_email'),
                Tables\Columns\TextColumn::make('company_website'),
                Tables\Columns\TextColumn::make('company_logo'),
                Tables\Columns\TextColumn::make('company_tracking'),
                Tables\Columns\TextColumn::make('barcode_label'),
                Tables\Columns\TextColumn::make('etransfer_email'),
                Tables\Columns\TextColumn::make('company_slogan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanyinfos::route('/'),
            'create' => Pages\CreateCompanyinfo::route('/create'),
            'edit' => Pages\EditCompanyinfo::route('/{record}/edit'),
        ];
    }    
}
