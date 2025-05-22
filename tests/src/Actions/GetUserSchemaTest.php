<?php


use CrescentPurchasing\FilamentAuditing\Actions\GetUserSchema;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;

beforeEach(function () {
    $this->getUserSchema = new GetUserSchema();
});

it('gets the default schema for the user form', function () {
    $schema = ($this->getUserSchema)();

    expect($schema)->toHaveCount(1)->toContainOnlyInstancesOf(Grid::class)
        ->and($schema[0]->getChildComponents())->toContainOnlyInstancesOf(Field::class)->toHaveCount(2);
});

it('allows specifying custom fields', function () {
    $customSchema = [
        TextInput::make('first_name'),
        TextInput::make('last_name'),
    ];

    FilamentAuditingPlugin::get()->userSchema($customSchema);

    $schema = ($this->getUserSchema)();

    expect($schema)->toHaveCount(3)->toContainOnlyInstancesOf(Component::class)
        ->and(array_pop($schema))->toBeInstanceOf(TextInput::class)->getName()->toBe('last_name')
        ->and(array_pop($schema))->toBeInstanceOf(TextInput::class)->getName()->toBe('first_name');
});
