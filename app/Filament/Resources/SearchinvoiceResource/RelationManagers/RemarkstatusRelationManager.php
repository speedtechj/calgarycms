<?php

namespace App\Filament\Resources\SearchinvoiceResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Statuscategory;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\SearchinvoiceResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RemarkstatusRelationManager extends RelationManager
{
    protected static string $relationship = 'remarkstatus';
    public static ?string $title = 'View Invoice Remark';
    protected static ?string $recordTitleAttribute = 'booking_invoice';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Remark Details')
                            ->schema([
                                Forms\Components\Select::make('statuscategory_id')
                                    ->label('Title')
                                    ->options(Statuscategory::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                                    ->required()
                                    ->visibleOn(['create','view']),
                                Forms\Components\TextInput::make('statuscat')
                                    ->label('Title')
                                    ->dehydrated(false)
                                    ->visibleOn('edit'),
                                Forms\Components\Select::make('assign_to')
                                    ->label('Assign To')
                                    ->multiple()
                                    ->options(User::where('branch_id', '!=', auth()->user()->branch_id)->pluck('full_name', 'id'))
                                    ->required()
                                    ->hiddenOn('edit'),
                                Forms\Components\MarkdownEditor::make('sender_comment')
                                    ->label('Sender Remarks/Comments')
                                    ->maxLength(65535)
                                    ->disabledOn('edit'),
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Document Attachements')
                            ->schema([
                                Forms\Components\MarkdownEditor::make('receiver_comment')
                                    ->label('Receiver Remarks/Comments')
                                    ->maxLength(65535)
                                    ->disabledOn('create'),
                                    Forms\Components\FileUpload::make('invoicedoc')
                                    ->label('Document Attachements')
                                    ->multiple()
                                    ->enableDownload()
                                    ->disk('public')
                                    ->directory('remarkstatus')
                                    ->visibility('private')
                                    ->enableOpen(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Open' => 'Open',
                                        'Closed' => 'Closed',
                                    ])
                                    ->required(),

                            ])

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                ->label('Ticket No'),
            Tables\Columns\TextColumn::make('booking.booking_invoice')
                ->label('Invoice No'),
            Tables\Columns\TextColumn::make('booking.manual_invoice')
                ->label('Manual Invoice'),
            Tables\Columns\TextColumn::make('statuscategory.description')
                ->label('Request/Complain Information'),
            Tables\Columns\TextColumn::make('status')
                ->label('Status'),
            Tables\Columns\IconColumn::make('is_resolved')
                ->boolean(),
            Tables\Columns\TextColumn::make('sender_comment'),
            Tables\Columns\TextColumn::make('receiver_comment'),
            Tables\Columns\TextColumn::make('assignby.full_name')
                ->label('Assigned By'),
            Tables\Columns\TextColumn::make('created_at')
                ->since(),
            Tables\Columns\TextColumn::make('updated_at')
                ->since(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Create Remark')
                ->modalHeading('Create New Remark')
                ->slideOver()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    $data['assign_by'] = auth()->id();

                    return $data;
                })
                ->after(function (array $data, Model $record) {
                    foreach ($data['assign_to'] as $assign_to) {
                        $recipients = $assign_to;
                        Notification::make()
                            ->title('New Request Created')
                            ->body($record->statuscategory->description . ' </br> ' .
                                'Created by' . ' ' . $record->user->first_name . ' ' . $record->user->last_name)
                            ->icon('heroicon-o-information-circle')
                            ->iconColor('danger')
                            ->actions([
                                Action::make('Reply')
                                  ->button()
                                  ->url(SearchinvoiceResource::getUrl('view', ['record' => $record->booking_id])),

                            ])
                            ->sendToDatabase(User::where('id', $recipients)->first());
                    }
                    // event(new Sendremarkemail($record));
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Edit Remark')
                ->slideOver()
                ->after(function (array $data, Model $record) {
                    // $record->update([
                    //     'is_resolved' => true,
                    // ]);

                    if ($record->status == 'Closed') {

                        $record->update([
                            'is_resolved' => true,
                        ]);
                        $recipients = $record->assign_by;
                        Notification::make()
                            ->title('Request Closed & Resolved')
                            ->body($record->statuscategory->description. '<br>'.' Resolved by' .' '.auth()->user()->full_name)
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->actions([
                                Action::make('View Reply')
                                ->button()
                                ->url(SearchinvoiceResource::getUrl('view', ['record' => $record->booking_id])),
                            ])
                            ->sendToDatabase(User::where('id', $recipients)->first());
                    }




                })
                ->mountUsing(fn (Forms\ComponentContainer $form, Model $record) => $form->fill([
                    'statuscat' => $record->statuscategory->description,
                    'invoicedoc' => $record->invoicedoc,
                    'sender_comment' => $record->sender_comment,
                    'receiver_comment' => $record->receiver_comment,
                    'status' => $record->status,
                ]))->visible(fn (Model $record) => $record->status == 'Open'),
                Tables\Actions\DeleteAction::make()->visible(fn (Model $record) => $record->status == 'Open'),
                Tables\Actions\ViewAction::make()
                ->slideOver()
                ->color('success')
                ->visible(fn (Model $record) => $record->status == 'Closed'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
