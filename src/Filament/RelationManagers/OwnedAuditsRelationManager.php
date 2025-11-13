<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\RelationManagers;

use CrescentPurchasing\FilamentAuditing\Filament\Concerns\WithCursorPagination;
use CrescentPurchasing\FilamentAuditing\Filament\Resources\Audits\AuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OwnedAuditsRelationManager extends RelationManager
{
    use WithCursorPagination;

    protected static string $relationship = 'ownedAudits';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-auditing::resource.owner_relation_title');
    }

    public function form(Schema $schema): Schema
    {
        return AuditResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return AuditResource::table($table);
    }
}
