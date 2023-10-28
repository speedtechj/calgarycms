<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\Receiver;
use Filament\Pages\Page;
use App\Models\Trackstatus;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use App\Exports\ManilamanifestExport;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;

use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Addstatinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Add Invoice Status';
    public static ?string $title = 'Add Invoice Status';
    protected ?string $maxContentWidth = 'full';
    protected static string $view = 'filament.pages.addstatinvoice';
    public $isedit = true;

    protected function getFormSchema(): array
    {
        return [
            Section::make('Invoice Update')
                ->schema([
                    Forms\Components\TextInput::make('invstatus')->required()

                ])->columns(2),


            // Forms\Components\Select::make('trackstatus_id')
            // ->options(Trackstatus::all()->pluck('name', 'id'))
            // ->required(),

        ];
    }
    protected function getTableQuery(): Builder
    {

        return Booking::query();
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')
                ->searchable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
                ->searchable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->tooltip(function (Model $record) {
                    // return whatever you need to show
                    // return $record->field1 + $record->field2
                    $status = Invoicestatus::where('booking_id', $record->id)->orderBy('id', 'desc')->get();
                    // dd($status);

                    return collect($status)->map(function ($status) {
                        return $status->trackstatus->description;
                    })->implode(',' . PHP_EOL);
                })
                ->getStateUsing(function (Model $record) {
                    // return whatever you need to show
                    // return $record->field1 + $record->field2
                    $status = Invoicestatus::where('booking_id', $record->id)->orderBy('id', 'desc')->get();
                    // dd($status);

                    return collect($status)->map(function ($status) {
                        return $status->trackstatus->description;
                    })->implode(',' . PHP_EOL);
                })->limit(10),



        ];
    }



    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('Update')
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->label('Update')
                ->action(function (Booking $record, array $data): void {
                    $statusupdate = InvoiceStatus::where('booking_id', $record->id)
                        ->where('trackstatus_id', $data['id'])
                        ->count();
                    if ($statusupdate == 0) {
                        Invoicestatus::create([
                            'generated_invoice' => $record->booking_invoice,
                            'manual_invoice' => $record->manual_invoice,
                            'provincephil_id' => $record->receiveraddress->provincephil_id,
                            'cityphil_id' => $record->receiveraddress->cityphil_id,
                            'booking_id' => $record->id,
                            'trackstatus_id' => $data['id'],
                            'date_update' => $data['date_updated'],
                            'remarks' => $data['remarks'],
                            'user_id' => auth()->user()->id,
                            'batch_id' => $record->batch_id,
                            'receiver_id' => $record->receiver_id,
                            'sender_id' => $record->sender_id,
                            'boxtype_id' => $record->boxtype_id,
                            'waybill' => $data['waybill'],
                            'waybill' => $data['location'],
                        ]);
                        Notification::make()
                            ->title('Update Status successfully')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Status Already Exist')
                            ->icon('heroicon-o-exclamation-circle')
                            ->iconColor('danger')
                            ->send();
                    }
                })
                ->form([
                    Forms\Components\Select::make('id')
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->required(),
                    Forms\Components\Datepicker::make('date_updated')
                        ->label('Date Updated')
                        ->required()
                        ->closeOnDateSelection(),
                    Forms\Components\TextInput::make('waybill'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\MarkdownEditor::make('remarks')
                ])
        ];
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage() == -1 ? $query->count() : $this->getTableRecordsPerPage());
    }
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25];
    }
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('province')
                ->label('Province')
                ->options(
                    function () {
                        // could be more discerning here, and select a distinct list of aircraft id's
                        // that actually appear in the Daily Logs, so we aren't presenting filter options
                        // which don't exist in the table, but in my case we know they are all used
                        return Provincephil::all()->pluck('name', 'id')->toArray();
                    }
                )
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // if we have a value (the aircraft ID from our options() query), just query a nested
                        // set of whereHas() clauses to reach our target, in this case two deep
                        $query->whereHas(
                            'receiveraddress',
                            fn (Builder $query) => $query->whereHas(
                                'provincephil',
                                fn (Builder $query) => $query->where('id', '=', (int) $data['value'])
                            )
                        );
                    }
                }),
        ];
    }
    // protected function getTableFiltersLayout(): ?string
    // {
    //     return Layout::AboveContent;
    // }
    protected function getTableBulkActions(): array
    {
        return [

            BulkAction::make('Update Status')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $statusupdate = InvoiceStatus::where('booking_id', $record->id)
                            ->where('trackstatus_id', $data['id'])
                            ->count();
                        if ($statusupdate == 0) {
                            Invoicestatus::create([
                                'generated_invoice' => $record->booking_invoice,
                                'manual_invoice' => $record->manual_invoice,
                                'provincephil_id' => $record->receiveraddress->provincephil_id,
                                'cityphil_id' => $record->receiveraddress->cityphil_id,
                                'booking_id' => $record->id,
                                'trackstatus_id' => $data['id'],
                                'date_update' => $data['date_updated'],
                                'remarks' => $data['remarks'],
                                'user_id' => auth()->user()->id,
                                'batch_id' => $record->batch_id,
                                'receiver_id' => $record->receiver_id,
                                'sender_id' => $record->sender_id,
                                'boxtype_id' => $record->boxtype_id,
                                'waybill' => $data['waybill'],
                                'waybill' => $data['location'],
                            ]);
                            Notification::make()
                                ->title('Update Status successfully')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('success')
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Status Already Exist')
                                ->icon('heroicon-o-exclamation-circle')
                                ->iconColor('danger')
                                ->send();
                        }
                    }
                })
                ->form([
                    Forms\Components\Select::make('id')
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->required(),
                    Forms\Components\Datepicker::make('date_updated')
                        ->label('Date Updated')
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\TextInput::make('waybill'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\MarkdownEditor::make('remarks')
                ])

        ];
    }
}
