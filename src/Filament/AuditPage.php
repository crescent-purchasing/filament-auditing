<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class AuditPage extends ManageRecords
{
    protected function makeTable(): Table
    {
        return FilamentAuditingPlugin::get()->getTable()::make($this);
    }

    protected function makeForm(): Form
    {
        return FilamentAuditingPlugin::get()->getForm()::make($this);
    }

    protected function paginateTableQuery(Builder $query): Paginator | CursorPaginator
    {
        if (! FilamentAuditingPlugin::get()->useCursorPagination()) {
            return parent::paginateTableQuery($query);
        }

        $count = $this->getTableRecordsPerPage() === 'all' ? $query->count() : $this->getTableRecordsPerPage();

        return $query->cursorPaginate($count);

    }
}
