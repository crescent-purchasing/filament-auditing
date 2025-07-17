<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\FilamentManager;
use Filament\Resources\Resource as FilamentResource;
use OwenIt\Auditing\Models\Audit;

readonly class RestoreAudit
{
    private FilamentManager $filament;

    private FilamentAuditingPlugin $plugin;

    public function __construct()
    {
        $this->filament = filament();

        $this->plugin = FilamentAuditingPlugin::get();
    }

    public function __invoke(Audit $record, bool $restoreToOld = false): void
    {
        $auditable = $record->auditable;

        $auditable->transitionTo($record, $restoreToOld);
        $auditable->save();
    }

    public function isAuthorized(Audit $record): bool
    {
        $permission = $this->plugin->getRestorePermission();

        if (!$auditable = $record->auditable) {
            return false;
        }

        /** @var class-string<FilamentResource> $resource */
        $resource = $this->filament->getModelResource($auditable);

        return $resource::can($permission, $auditable);
    }

    public function isEnabled(Audit $record): bool
    {
        $disabledEvents = ['deleted', 'restored'];

        if (in_array($record->event, $disabledEvents)) {
            return false;
        }

        return true;
    }
}
