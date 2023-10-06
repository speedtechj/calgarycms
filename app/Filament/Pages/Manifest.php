<?php

namespace App\Filament\Pages;
use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\Receiver;
use Filament\Pages\Page;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Manifest extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Report';
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
            ->label('Generated Invoice')
            ->searchable(isIndividual: true, isGlobal: false)
            ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
            ->label('Manual Invoice')
            ->searchable(isIndividual: true, isGlobal: false)
            ->sortable(),
            Tables\Columns\TextColumn::make('Quantity')
            ->label('Quantity')
            ->default('1'),
            Tables\Columns\TextColumn::make('boxtype.description')
            ->label('Box Type')
            ->sortable(),
            Tables\Columns\TextColumn::make('batch.id')
                    ->label('Batch No')
                    ->sortable()

                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno ."-". $record->batch->batch_year;
                    }),
            Tables\Columns\TextColumn::make('sender.full_name')
            ->label('Sender Name')
           
            ->sortable()
            ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
            ->label('Receiver Name')
           
            ->sortable()
    ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('receiveraddress.address')
            ->label('Address')
           
            ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
            ->label('Barangay')
            ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
            ->label('Province')
            ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
            ->label('City')
           ->sortable(),
            Tables\Columns\TextColumn::make('receiver.mobile_no')
            ->label('Mobile No')
           ->sortable(),
            Tables\Columns\TextColumn::make('receiver.home_no')
            ->label('Home No')
           ->sortable(),
            
                ];
    } 
    protected function getTableActions(): array
    {
       return[
        ActionGroup::make([
            Tables\Actions\Action::make('assignnewbatch')
            ->label('Assign New Batch')
            ->form([
                Select::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'id', fn (Builder $query) => $query->where('is_active', '1'))
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->batchno} {$record->batch_year}"),
            ])
            ->action(function (Booking $record, array $data): void {
               
                $record->update([
                    'batch_id' => $data['batch_id'],
                ]);
            }),
        ])
        ];
    }
    protected function getTableFilters(): array
{
    return [
       
    
        SelectFilter::make('batch_id')
        ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
        ->default(),
    
       
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
// {
//     return $query->simplePaginate($this->getTableRecordsPerPage() == 'all' ? $query->count() : $this->getTableRecordsPerPage());
// }
// protected function shouldPersistTableFiltersInSession(): bool
// {
//     return true;
// }
}
