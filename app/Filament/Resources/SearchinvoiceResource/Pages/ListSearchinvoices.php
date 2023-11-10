<?php

namespace App\Filament\Resources\SearchinvoiceResource\Pages;

use App\Filament\Resources\SearchinvoiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListSearchinvoices extends ListRecords
{
    protected static string $resource = SearchinvoiceResource::class;
    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage() == -1 ? $query->count() : $this->getTableRecordsPerPage());
    }
    protected function getTableRecordsPerPageSelectOptions(): array 
        {
            return [10, 25];
        } 
   
}
