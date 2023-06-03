<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Branch;
use App\Models\Provincecan;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\BranchResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BranchResource\Pages\EditBranch;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Filament\Resources\BranchResource\Pages\CreateBranch;
use App\Filament\Resources\BranchResource\Pages\ListBranches;


class BranchResource extends Resource
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $model = Branch::class;


    protected static ?string $navigationIcon = 'heroicon-o-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Company/Business Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
                            TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),
                            DatePicker::make('birth_date')
                            ->required(),
                        TextInput::make('address')
                            ->label('Personal/Business Address')
                            ->required()
                            ->maxLength(255),
                            Select::make('provincecan_id')
                            ->label('Province')
                            ->required()
                            ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(fn (callable $set) => $set('citycan_id', null)),
                            Select::make('citycan_id')
                            ->label('City')
                            ->searchable()
                            ->required()
                            ->options(function (callable $get) {
                                $province = Provincecan::find($get('provincecan_id'));
                                if (!$province) {
                                    // return Citycan::all()->pluck('name', 'id');
                                    return null;
                                }
                                return $province->citycan->pluck('name', 'id');
                            }),
                        TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('mobile_no')
                            ->label('Mobile Number')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone_no')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                            FileUpload::make('file_doc')
                            ->label('Document Attachements')
                            ->multiple()
                            ->enableDownload()
                    ->disk('public')
                    ->directory('branch')
                    ->visibility('private')
                    ->enableOpen(),
                    MarkdownEditor::make('note')
                        ->label('Note')
                        ->columnSpan(2)
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('branchid')
                ->label('Account Number')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('business_name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('citycan.name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('postal_code')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('mobile_no')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('phone_no')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('note')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                ->getStateUsing(function(Model $record) {
                    return $record->user->first_name ." " .$record->user->last_name;
                })
                ->label('Created By')
                ->toggleable()
                ->sortable(),
                // Tables\Columns\TextColumn::make('user_id'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
