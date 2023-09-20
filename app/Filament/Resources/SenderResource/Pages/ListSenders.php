<?php

namespace App\Filament\Resources\SenderResource\Pages;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SenderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSenders extends ListRecords
{
    protected static string $resource = SenderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->simplePaginate($this->getTableRecordsPerPage() == 'all' ? $query->count() : $this->getTableRecordsPerPage());
}
}
