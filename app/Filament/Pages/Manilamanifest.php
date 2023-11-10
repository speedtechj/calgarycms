<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\Receiver;
use Filament\Pages\Page;
use App\Exports\CollectionExport;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use App\Exports\ManilamanifestExport;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Manilamanifest extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Customer Service';
    protected static string $view = 'filament.pages.manifest';
    public $isedit = true;
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
            Tables\Columns\TextColumn::make('Quantity')
                ->label('Quantity')
                ->default('1'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('batch.id')
                ->label('Batch No')
                ->sortable()
                ->searchable()
                ->getStateUsing(function (Model $record) {
                    return $record->batch->batchno . "-" . $record->batch->batch_year;
                }),
            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable()
                ->sortable()
                ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable()
                ->sortable()
                ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('receiveraddress.address')
                ->label('Address')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
                ->label('Barangay')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiver.mobile_no')
                ->label('Mobile No')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiver.home_no')
                ->label('Home No')
                ->searchable()->sortable(),

        ];
    }
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('manifest')
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->label('Export Packinglist')
                ->url(fn (Booking $record) => route('packlistdownload', $record))
                ->openUrlInNewTab(),
        ];
    }
    protected function getTableFilters(): array
    {
        return [


            SelectFilter::make('batch_id')
                ->multiple()
                ->label('Batch Number')
                ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                ->default(array('Select Batch Number')),

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
    // protected function paginateTableQuery(Builder $query): Paginator
    protected function getTableBulkActions(): array
    {
        return [

            Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                ->icon('heroicon-o-document-download')
                ->action(fn (Collection $records) => (new ManilamanifestExport($records))->download('manifest.xlsx')),

        ];
    }
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50];
    }
    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage() == -1 ? $query->count() : $this->getTableRecordsPerPage());
    }
    protected function shouldPersistTableSearchInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTableColumnSearchInSession(): bool
    {
        return true;
    }
}
