<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Concerns;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

trait WithCursorPagination
{
    protected function paginateTableQuery(Builder $query): Paginator | CursorPaginator
    {
        if (! FilamentAuditingPlugin::get()->useCursorPagination()) {
            return parent::paginateTableQuery($query);
        }

        $count = $this->getTableRecordsPerPage() === 'all' ? $query->count() : $this->getTableRecordsPerPage();

        return $query->cursorPaginate($count);

    }
}
