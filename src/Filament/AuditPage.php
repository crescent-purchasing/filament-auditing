<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class AuditPage extends ManageRecords
{
    protected static string $resource = AuditResource::class;

    protected function paginateTableQuery(Builder $query): Paginator | CursorPaginator
    {
        if (! FilamentAuditingPlugin::get()->useCursorPagination()) {
            return parent::paginateTableQuery($query);
        }

        $count = $this->getTableRecordsPerPage() === 'all' ? $query->count() : $this->getTableRecordsPerPage();

        return $query->cursorPaginate($count);

    }
}
