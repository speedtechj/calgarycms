<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Provincecan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AgentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentResource\RelationManagers;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('home_no')
                        ->maxLength(255),
                        Forms\Components\FileUpload::make('filedoc')
                        ->label('Document Attachements')
                        ->multiple()
                        ->enableDownload()
                        ->disk('public')
                        ->directory('agent')
                        ->visibility('private')
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

                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('provincecan.name'),
                Tables\Columns\TextColumn::make('citycan.name'),
                Tables\Columns\TextColumn::make('postal_code'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date(),
                Tables\Columns\TextColumn::make('filedoc'),
                Tables\Columns\TextColumn::make('mobile_no'),
                Tables\Columns\TextColumn::make('home_no'),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date(),
                Tables\Columns\TextColumn::make('note'),
                IconColumn::make('agent_type')->label('In-House Agent')->boolean(),
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
