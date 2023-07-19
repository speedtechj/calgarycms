<?php

namespace App\Filament\Pages;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Pages\Page;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Manifest extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
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
            Tables\Columns\TextColumn::make('batch.id')
                    ->label('Batch No')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno ."-". $record->batch->batch_year;
                    }),
            Tables\Columns\TextColumn::make('sender.full_name')
            ->label('Sender Name')
            ->searchable()
            ->sortable(),
            Tables\Columns\TextColumn::make('receiver.full_name')
            ->label('Receiver Name')
            ->searchable()
            ->sortable(),
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
    protected function getTableFilters(): array
{
    return [
       
    
        SelectFilter::make('batch_id')
        ->options(Batch::all()->where('is_active',true)->pluck('batchno','id'))
        ->placeholder('Select Batch Number')
        ->label('Batch Number')
        ->default('0'),
       
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

}
