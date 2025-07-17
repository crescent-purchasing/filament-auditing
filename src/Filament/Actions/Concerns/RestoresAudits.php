<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditSchema;
use CrescentPurchasing\FilamentAuditing\Actions\GetModifiedFields;
use CrescentPurchasing\FilamentAuditing\Actions\RestoreAudit;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

trait RestoresAudits
{
    public static function getDefaultName(): ?string
    {
        return 'restoreAudit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-auditing::resource.actions.restore_audit.label'));

        $this->icon('heroicon-o-arrow-path');

        $this->disabled(function (RestoreAudit $restore, Audit $record): bool {
            if (! $restore->isEnabled($record)) {
                return true;
            }

            if (! $restore->isAuthorized($record)) {
                return true;
            }

            return false;

        });

        $this->form(function (Audit $record, GetAuditSchema $schema, GetModifiedFields $fields): array {
            $keys = array_keys($record->new_values);

            $fromSchema = $schema($record->auditable->toArray(), $keys);

            return [
                Toggle::make('restore_to_old')
                    ->label(__('filament-auditing::resource.actions.restore_audit.restore_to_old'))
                    ->inlineLabel()
                    ->disabled(fn (): bool => $record->event !== 'updated')
                    ->live(),
                Section::make(__('filament-auditing::resource.actions.restore_audit.restore_from_values'))
                    ->collapsed()
                    ->schema($fromSchema),
                Section::make(__('filament-auditing::resource.actions.restore_audit.restore_to_values'))
                    ->collapsed(false)
                    ->schema(fn (Get $get): array => $schema($fields($record, $get('restore_to_old')))),
            ];
        });

        $this->action(function (RestoreAudit $restore, Audit $record, array $data): void {
            $restore($record, $data['restore_to_old'] ?? false);
        });

        $this->after(function (Component $livewire): void {
            $livewire->dispatch('auditRestored');
        });
    }
}
