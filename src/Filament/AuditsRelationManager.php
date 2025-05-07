<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditsRelationManager extends RelationManager
{
    protected static string $relationship = 'audits';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-auditing::resource.relation.title');
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
