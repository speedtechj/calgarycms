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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Updatestatinvoice extends Page implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use InteractsWithForms;
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Edit/View/Delete Invoice Status';
    public static ?string $title = 'Edit/View/Delete Invoice Status';
    protected ?string $maxContentWidth = 'full';
    protected static string $view = 'filament.pages.updatestatinvoice';
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

        return Invoicestatus::query();
    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('generated_invoice')
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
            Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province')
                ->searchable(),
            Tables\Columns\TextColumn::make('cityphil.name')
                ->label('City')
                ->searchable(),
            Tables\Columns\TextColumn::make('trackstatus.description')
                ->label('Status'),
                Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill'),
                Tables\Columns\TextColumn::make('location')
                ->label('Location'),
                Tables\Columns\TextColumn::make('remarks')
                ->label('remarks'),




        ];
    }



    protected function getTableActions(): array
    {
        return [
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
            Tables\Actions\DeleteAction::make()
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
            SelectFilter::make('provincephil_id')
                ->label('Batch')
                ->relationship('provincephil', 'name'),
            SelectFilter::make('trackstatus_id')
                ->label('Status')
                ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                
               
        ];
    }
    // protected function getTableFiltersLayout(): ?string
    // {
    //     return Layout::AboveContent;
    // }
    protected function getTableBulkActions(): array
    {
        return [

            BulkAction::make('delete')
                ->action(fn (Collection $records) => $records->each->delete()),
            BulkAction::make('Edit Status')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([

                            'date_update' => $data['date_update'],
                        'remarks' => $data['remarks'],
                        'location' => $data['location'],
                        'waybill' => $data['waybill'],
                        ]);
                    }
                })
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

        ];
    }
}
