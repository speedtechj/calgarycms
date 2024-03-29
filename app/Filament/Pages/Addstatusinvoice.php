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
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\FormsComponent;
use Illuminate\Contracts\Pagination\Paginator;
class Addstatusinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
  
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Add Batch Status';
    public static ?string $title = 'Add Batch Status';
    protected static string $view = 'filament.pages.manifest';
    public $isedit = true;
    protected function getTableQuery(): Builder
    {

        return Booking::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('batch.batchno')
                ->label('Batch Number')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable()
                ->sortable(),
                TagsColumn::make('invoicestatus.trackstatus.description'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable()
                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver')
                ->searchable()
                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
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
                ->label('Batch Number')
                ->default(array('Select Batch Number')),
            SelectFilter::make('province')
                ->label('Province')
                ->searchable()
                ->options(
                    function () {
                        return Provincephil::all()->pluck('name', 'id')->toArray();
                    }
                )
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        
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
    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }
    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }
    protected function getTableBulkActions(): array
    {
        return [

            BulkAction::make('Update Batch/Invoice Status')
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
                                'location' => $data['location'],
                                'waybill' => $data['waybill'],
                            ]);
                        }
                    }
                })
                ->form([
                    Forms\Components\Select::make('id')
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->required(),
                        Datepicker::make('date_updated')
                        ->label('Update Date')
                        ->default(now())
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\TextInput::make('waybill'),
                    
                    Forms\Components\Textarea::make('remarks')
                ])
        ];
    }
    // protected function shouldPersistTableFiltersInSession(): bool
    // {
    //     return true;
    // }
    protected function getTableRecordsPerPageSelectOptions(): array 
    {
        return [10, 25, 50, 100];
    } 
}


// class Addstatusinvoice extends Page
// {
//     protected static ?string $navigationIcon = 'heroicon-o-document-text';

//     protected static string $view = 'filament.pages.addstatusinvoice';
// }
