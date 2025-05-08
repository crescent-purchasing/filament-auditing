<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Audit;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\User;
use Livewire\Component;

trait RestoresAudits
{
    use HasPermission;

    public static function getDefaultName(): ?string
    {
        return 'restoreAudit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-auditing::resource.actions.restore_audit.label'));

        $this->icon('heroicon-o-arrow-path');

        $this->disabled(function (Audit $record, AuthManager $auth): bool {
            if ($record->event !== 'updated') {
                return true;
            }

            $permission = $this->getPermission();

            /** @var User $user */
            $user = $auth->user();

            return $user->cannot($permission, $record->auditable);

        });

        $this->form(function (Audit $record): array {
            $keys = array_keys($record->new_values);

            $auditSchema = FilamentAuditingPlugin::get()->getAuditSchema();

            $fromSchema = $auditSchema::invoke($keys, $record->auditable->toArray());

            return [
                Toggle::make('restore_to_old')
                    ->label(__('filament-auditing::resource.actions.restore_audit.restore_to_old'))
                    ->inlineLabel()
                    ->live(),
                Section::make(__('filament-auditing::resource.actions.restore_audit.restore_from_values'))
                    ->collapsed()
                    ->schema($fromSchema),
                Section::make(__('filament-auditing::resource.actions.restore_audit.restore_to_values'))
                    ->collapsed(false)
                    ->schema(fn (Get $get): array => $auditSchema::invoke(
                        $keys,
                        $get('restore_to_old')
                            ? $record->getModifiedByType('old')
                            : $record->getModifiedByType('new')
                    )),
            ];
        });

        $this->action(function (Audit $record, array $data): void {
            $auditable = $record->auditable;

            $auditable->transitionTo($record, $data['old']);
            $auditable->save();

        });

        $this->after(function (Component $livewire): void {
            $livewire->dispatch('auditRestored');
        });
    }
}
