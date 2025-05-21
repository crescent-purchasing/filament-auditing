<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Concerns;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait WithCursorPagination
{
    /**
     * @param  Builder<Model>  $query
     * @return Paginator<int, Model>|CursorPaginator<int, Model>
     */
    protected function paginateTableQuery(Builder $query): Paginator | CursorPaginator
    {
        if (! FilamentAuditingPlugin::get()->useCursorPagination()) {
            return parent::paginateTableQuery($query);
        }

        $count = is_string($this->getTableRecordsPerPage()) ? $query->count() : $this->getTableRecordsPerPage();

        return $query->cursorPaginate($count);

    }
}
