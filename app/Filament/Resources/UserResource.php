<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Pages\EditUser;
use App\Models\User;
use Filament\Tables;
use App\Models\Provincecan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255)
                            ->label('First Name'),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Last Name')->columns(2),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255)
                            ->label('Address'),
                        Select::make('provincecan_id')
                            ->label('Province')
                            ->required()
                            ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(fn (callable $set) => $set('citycan_id', null)),
                        Select::make('citycan_id')
                            ->label('City')
                            ->required()
                            ->searchable()
                            ->options(function (callable $get) {
                                $province = Provincecan::find($get('provincecan_id'));
                                if (!$province) {
                                    // return Citycan::all()->pluck('name', 'id');
                                    return null;
                                }
                                return $province->citycan->pluck('name', 'id');
                            }),
                        Forms\Components\TextInput::make('postal_code')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('a0a 0a0'))
                            ->required()
                            ->maxLength(255)
                            ->label('Postal Code'),
                        Forms\Components\TextInput::make('mobile_no')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000)000-0000'))
                            ->required()
                            ->maxLength(255)
                            ->label('Mobile Number'),
                        Forms\Components\TextInput::make('home_no')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000)000-0000'))
                            ->maxLength(255)
                            ->label('Home Number'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email Address'),

                        DatePicker::make('birth_date')
                            ->required()
                            ->label('Date of Birth'),
                        DatePicker::make('date_hire')
                            ->required()
                            ->label('Date Hire'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255)
                            ->hiddenOn(Pages\EditUser::class),
                            Forms\Components\Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                        FileUpload::make('file_doc')
                            ->label('Document Attachements')
                            ->multiple()
                            ->enableDownload()
                            ->disk('public')
                            ->directory('user')
                            ->visibility('private')
                            ->enableOpen(),
                        Forms\Components\Select::make('branch_id')
                            ->required()
                            ->relationship('branch', 'business_name'),
                        Toggle::make('is_active')->label('Active'),
                        MarkdownEditor::make('note')
                            ->label('Note')
                            ->columnSpan('full'),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                    ->label('Province')
                    ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('citycan.name')
                    ->label('City')
                    ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('mobile_no')
                    ->label('Mobile Number')
                    ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('home_no')
                    ->label('Home Number')
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('branch.business_name')
                    ->label('Branch')
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
