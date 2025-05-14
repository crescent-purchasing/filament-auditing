<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms\ViewOwnerAction as ViewOwnerFormAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;

readonly class GetUserSchema
{
    /**
     * @return Component[]
     */
    public function __invoke(): array
    {
        $baseSchema = Grid::make(3)->schema([
            TextInput::make('email')
                ->label(__('filament-auditing::resource.fields.user.email'))
                ->columnSpan(2),
            TextInput::make('id')
                ->label(__('filament-auditing::resource.fields.user.id'))
                ->suffixAction(ViewOwnerFormAction::make()),
        ]);

        $userSchema = FilamentAuditingPlugin::get()->getUserSchema();

        return [$baseSchema, ...$userSchema];
    }

}
