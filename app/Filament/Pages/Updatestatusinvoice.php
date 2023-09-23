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
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;

class Updatestatusinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
  
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Update/Edit Invoice';
    
    public static ?string $label = 'Update/Edit Invoice';
    
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
                
        ];
    }
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('batch_id')

                ->options(Batch::all()->where('is_active', true)->pluck('batchno', 'id'))
                ->placeholder('Select Batch Number')
                ->label('Batch Number')
                ->default('0'),
                SelectFilter::make('trackstatus_id')
                ->label('Status')
                ->options(Trackstatus::all()->pluck('description', 'id'))
                ->searchable()
                ->default('1'),
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
       return[
        Tables\Actions\DeleteAction::make()
        ->label('Delete'),
        Tables\Actions\Action::make('edit')
        ->label('Edit')
        ->mountUsing(fn (Forms\ComponentContainer $form, Invoicestatus $record) => $form->fill([
            'remarks' => $record->remarks,
            'date_update' => $record->date_update,
        ]))
        ->form([
                    Datepicker::make('date_update')
                        ->label('Date Updated')
                        ->required(),
                    Forms\Components\Textarea::make('remarks')
        ])
        ->action(function (Invoicestatus $record, array $data): void {
           
            $record->update([
                'date_update' => $data['date_update'],
                'remarks' => $data['remarks'],
            ]);
        }),
       ];
    }
    protected function getTableBulkActions(): array
    {
        return [

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
                    
                    Datepicker::make('date_updated')
                        ->label('Date Updated')
                        ->required(),
                    Forms\Components\Textarea::make('remarks')
                ])
        ];
    }
    // protected function shouldPersistTableFiltersInSession(): bool
    // {
    //     return true;
    // }
}


// class Updatestatusinvoice extends Page
// {
//     protected static ?string $navigationIcon = 'heroicon-o-document-text';

//     protected static string $view = 'filament.pages.updatestatusinvoice';
// }
