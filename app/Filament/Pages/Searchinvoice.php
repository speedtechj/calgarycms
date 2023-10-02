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
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Searchinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Search Invoice';
    protected static ?string $title = "Search Invoice";
    protected static string $view = 'filament.pages.searchinvoice';
    public $isedit = true;
    protected function getTableQuery(): Builder
    {

        return Booking::query();
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Generated Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('batch.id')
                ->label('Batch Number')
                ->sortable()
                ->getStateUsing(function (Model $record) {
                    return $record->batch->batchno . " " . $record->batch->batch_year;
                }),
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
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')

                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')

                ->sortable(),

        ];
    }
    protected function getTableFilters(): array
    {
        return [
            Filter::make('booking_date')
    ->form([
        Card::make()
        ->label('Filter by booking date.')
        ->schema([
            Forms\Components\DatePicker::make('book_from')->closeOnDateSelection()->default(now()),
            Forms\Components\DatePicker::make('book_until')->closeOnDateSelection()->default(now()),
        ]),
       
    ])->columnSpan(2)
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['book_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
            )
            ->when(
                $data['book_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
            );
    })
        ];
    }
    // protected function getTableFiltersLayout(): ?string
    // {
    //     return Layout::AboveContent;
    // }
    // protected function getTableFiltersFormColumns(): int
    // {
    //     return 3;
    // }
    protected function getTableBulkActions(): array
    {
        return [

            BulkAction::make('Update Batch')
                ->action(function (Collection $records, array $data): void {
                   
                    foreach ($records as $record) {
                        $record->update([
                            'batch_id' => $data['batch_id'],
                        ]);
                  
                    }
                })
                ->form([
                    Select::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'id', fn (Builder $query) => $query->where('is_active', '1'))
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->batchno} {$record->batch_year}"),
                ])
        ];
    }
    protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->simplePaginate($this->getTableRecordsPerPage() == 'all' ? $query->count() : $this->getTableRecordsPerPage());
}
    
}


