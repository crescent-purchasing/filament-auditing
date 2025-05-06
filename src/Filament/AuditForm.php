<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Contracts\AuditContract as Audit;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components;
use Filament\Forms\Form;

class AuditForm extends Form
{
    protected function setUp(): void
    {
        $this->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Components\Tabs::make('AuditTabs')
                ->contained(false)
                ->columnSpanFull()
                ->schema([
                    Components\Tabs\Tab::make('Metadata')
                        ->inlineLabel()
                        ->schema([
                            Components\DateTimePicker::make('created_at')
                                ->inlineLabel()
                                ->label('Recorded at'),
                            Components\TextInput::make('event'),
                            Components\TextInput::make('url'),
                            Components\TextInput::make('ip_address'),
                            Components\TextInput::make('user_agent'),
                            Components\TextInput::make('tags'),
                        ]),
                    Components\Tabs\Tab::make('User Data')
                        ->inlineLabel()
                        ->hidden(fn (Audit $record) => empty($record->user))
                        ->schema([
                            Components\Grid::make(1)
                                ->relationship('user')
                                ->schema([
                                    Components\TextInput::make('id')
                                        ->suffixAction(ViewUserAction::make()),
                                    Components\TextInput::make('full_name'),
                                    Components\TextInput::make('email'),
                                ]),
                        ]),
                    Components\Tabs\Tab::make('Old values')
                        ->hidden(fn (Audit $record) => empty($record->old_values))
                        ->schema(function (Audit $record): array {
                            $keys = array_keys($record->old_values);
                            $mappedFields = $record->getModifiedByType('old');

                            return FilamentAuditingPlugin::get()->invokeAuditSchema($keys, $mappedFields);
                        }),
                    Components\Tabs\Tab::make('New values')
                        ->schema(function (Audit $record): array {
                            $keys = array_keys($record->new_values);
                            $mappedFields = $record->getModifiedByType('new');

                            return FilamentAuditingPlugin::get()->invokeAuditSchema($keys, $mappedFields);
                        }),
                ]),
        ];
    }
}
