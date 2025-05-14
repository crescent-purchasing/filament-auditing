<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;

readonly class GetUserSchema
{
    private FilamentAuditingPlugin $plugin;

    public function __construct()
    {
        $this->plugin = FilamentAuditingPlugin::get();
    }

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
                ->suffixAction(ViewUserAction::make()),
        ]);

        $userSchema = $this->plugin->getUserSchema();

        return [$baseSchema, ...$userSchema];
    }
}
