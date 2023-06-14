<?php

namespace App\Filament\Resources;

use Filament\Forms;
use TextInput\Mask;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Citycan;
use App\Models\Provincecan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\AgentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Filament\Resources\AgentResource\RelationManagers\BookingRelationManager;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([

                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->label('Address')
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
                        ->mask(fn ($mask) => $mask->pattern('a0a 0a0'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('date_of_birth')->label('Date of Birth')
                        ->required(),

                    Forms\Components\DatePicker::make('date_hired')->label('Date Started
                    ')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('mobile_no')
                        ->mask(fn ($mask) => $mask->pattern('(000)000-0000'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('home_no')
                        ->mask(fn ($mask) => $mask->pattern('(000)000-0000'))
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('filedoc')
                        ->label('Document Attachements')
                        ->multiple()
                        ->enableDownload()
                        ->disk('public')
                        ->directory('agent')
                        ->visibility('private')
                        ->minSize(4)
                        ->maxSize(1024)
                        ->enableOpen(),
                    Toggle::make('agent_type')->label('In-House Agent'),
                    Forms\Components\MarkdownEditor::make('note')
                        ->maxLength(65535)->columnSpan('full'),
                ])->columns(3)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('provincecan.name')
                    ->label('Province')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('citycan.name')
                    ->label('City')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('postal_code')
                    ->formatStateUsing(function (string $state) {
                        $formattedpostal = substr($state, 0, 3) . " " . substr($state, 3, 3);
                        return $formattedpostal;
                    })
                    ->label('Postal Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('mobile_no')
                ->formatStateUsing(function ( $state) {
                    if($state != null){
                    $formattedNumber = "(" . substr($state, 0, 3) . ") " . substr($state, 3, 3) . "-" . substr($state, 6);
                    return $formattedNumber;
                    }
                })
                    ->label('Mobile No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('home_no')
                    ->label('Home No.')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ( $state) {
                        if($state != null){
                            $formattedNumber = "(" . substr($state, 0, 3) . ") " . substr($state, 3, 3) . "-" . substr($state, 6);
                            return $formattedNumber;
                            }
                    })
                    
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->toggleable($isToggledHiddenByDefault = true),
                Tables\Columns\TextColumn::make('note'),
                IconColumn::make('agent_type')->label('In-House Agent')->boolean(),
                Tables\Columns\TextColumn::make('user_id')
                    ->toggleable($isToggledHiddenByDefault = true)
                    ->label('Encoder')
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable($isToggledHiddenByDefault = true)
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable($isToggledHiddenByDefault = true)
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
            BookingRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
