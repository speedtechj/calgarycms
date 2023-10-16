<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\Receiver;
use Filament\Pages\Page;
use App\Models\Trackstatus;
use Filament\Pages\Actions;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Contracts\Pagination\Paginator;

class Updatestatusinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Edit/View Invoice';

    public static ?string $label = 'Edit/View Invoice';

    protected static ?string $navigationGroup = 'Invoice Status';

    protected static string $view = 'filament.pages.manifest';
    public $isedit = true;

    protected function getTableQuery(): Builder
    {

        return Invoicestatus::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('generated_invoice')
                ->label('Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->label('Generated Invoice')
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->sortable(),
            Tables\Columns\TextColumn::make('trackstatus.description')
                ->label('Status')
                ->sortable(),
            Tables\Columns\TextColumn::make('batch.batchno')
                ->label('Batch Number')
                ->sortable(),
            Tables\Columns\TextColumn::make('date_update')
                ->label('Status Date')

                ->sortable()
                ->date('Y-m-d'),
            Tables\Columns\TextColumn::make('remarks'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')

                ->sortable(),

            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province')

                ->sortable(),
            Tables\Columns\TextColumn::make('cityphil.name')
                ->label('City')

                ->sortable(),
            Tables\Columns\TextColumn::make('location')
                ->label('Location')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill')
                ->searchable()
                ->sortable(),

        ];
    }
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('batch_id')
                ->multiple()
                ->options(Batch::all()->where('is_active', true)->pluck('batchno', 'id'))
                ->placeholder('Select Batch Number')
                ->label('Batch Number')
                ->default(array('Select Batch Number')),
            SelectFilter::make('trackstatus_id')
                ->multiple()
                ->label('Status')
                ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                ->searchable(),
                // ->default(array('Select Status')),
            SelectFilter::make('provincephil_id')
                ->label('Province')
                ->options(Provincephil::all()->pluck('name', 'id'))
                ->searchable(),



        ];
    }
    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }
    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\DeleteAction::make()
                ->label('Delete'),
            Tables\Actions\Action::make('Edit Invoice Status')
                ->label('Edit')
                ->mountUsing(fn (Forms\ComponentContainer $form, Invoicestatus $record) => $form->fill([
                    'remarks' => $record->remarks,
                    'date_update' => $record->date_update,
                    'location' => $record->location,
                    'waybill' => $record->waybill,
                ]))
                ->form([
                    DatePicker::make('date_update')
                        ->label('Date Updated')
                        ->default(now())
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\TextInput::make('waybill'),
                    Forms\Components\Textarea::make('remarks')
                ])
                ->action(function (Invoicestatus $record, array $data): void {

                    $record->update([
                        'date_update' => $data['date_update'],
                        'remarks' => $data['remarks'],
                        'location' => $data['location'],
                        'waybill' => $data['waybill'],
                    ]);
                }),
        ];
    }
    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('delete')
                ->action(fn (Collection $records) => $records->each->delete()),
            BulkAction::make('Update Status')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([

                            'date_update' => $data['date_updated'],
                            'remarks' => $data['remarks'],
                        ]);
                    }
                })
                ->form([

                    DatePicker::make('date_updated')
                        ->label('Date Stats Updated')
                        ->default(now())
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\Textarea::make('remarks')
                ])
        ];
    }
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }
    // protected function shouldPersistTableFiltersInSession(): bool
    // {
    //     return true;
    // }
}
