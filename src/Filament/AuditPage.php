<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Filament\Concerns\WithCursorPagination;
use Filament\Resources\Pages\ManageRecords;

class AuditPage extends ManageRecords
{
    use WithCursorPagination;

    protected static string $resource = AuditResource::class;
}
