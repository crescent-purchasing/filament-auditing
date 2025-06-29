<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Actions\Concerns;

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditSchema;
use CrescentPurchasing\FilamentAuditing\Actions\GetModifiedFields;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\User;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

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
            $disabledEvents = ['deleted', 'restored'];

            if (in_array($record->event, $disabledEvents)) {
                return true;
            }

            $permission = $this->getPermission();

            /** @var User $user */
            $user = $auth->user();

            return $user->cannot($permission, $record->auditable);

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

        $this->action(function (Audit $record, array $data): void {
            $auditable = $record->auditable;

            $auditable->transitionTo($record, $data['restore_to_old'] ?? false);
            $auditable->save();

        });

        $this->after(function (Component $livewire): void {
            $livewire->dispatch('auditRestored');
        });
    }
}
