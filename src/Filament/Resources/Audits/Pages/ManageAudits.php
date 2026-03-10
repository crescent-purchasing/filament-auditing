<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Resources\Audits\Pages;

use CrescentPurchasing\FilamentAuditing\Filament\Concerns\WithCursorPagination;
use CrescentPurchasing\FilamentAuditing\Filament\Resources\Audits\AuditResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAudits extends ManageRecords
{
    use WithCursorPagination;

    protected static string $resource = AuditResource::class;
}
