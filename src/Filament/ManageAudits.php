<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Filament\Concerns\WithCursorPagination;
use Filament\Resources\Pages\ManageRecords;

class ManageAudits extends ManageRecords
{
    use WithCursorPagination;

    protected static string $resource = AuditResource::class;
}
