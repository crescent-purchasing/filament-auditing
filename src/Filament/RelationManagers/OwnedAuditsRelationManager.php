<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\RelationManagers;

use CrescentPurchasing\FilamentAuditing\Filament\AuditResource;
use CrescentPurchasing\FilamentAuditing\Filament\Concerns\WithCursorPagination;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
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

    public function form(Form $form): Form
    {
        return AuditResource::form($form);
    }

    public function table(Table $table): Table
    {
        return AuditResource::table($table);
    }
}
