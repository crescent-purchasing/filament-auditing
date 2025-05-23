<?php

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditSchema;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;

beforeEach(function () {
    $this->getAuditSchema = new GetAuditSchema;

    $this->values = [
        'title' => 'I am a title!',
        'value' => 123,
        'json' => json_encode([
            'title' => 'I am a JSON field!',
            'description' => 'I should become a textarea!',
        ]),
    ];
});

it('gets the form schema from values & keys', function () {
    $keys = array_keys($this->values);

    $schema = ($this->getAuditSchema)($this->values, $keys);

    expect($schema)
        ->toBeArray()->toHaveCount(3)
        ->toContainOnlyInstancesOf(Component::class);
});

it('does not include values not in keys', function () {
    $keys = ['title'];

    $schema = ($this->getAuditSchema)($this->values, $keys);

    expect($schema)->toBeArray()->toHaveCount(1)
        ->and($schema[0])->toBeInstanceOf(Placeholder::class)
        ->and($schema[0]->getName())->toBe('title');
});

it('gets the form schema from only values', function () {
    $schema = ($this->getAuditSchema)($this->values);

    expect($schema)
        ->toBeArray()->toHaveCount(3)
        ->toContainOnlyInstancesOf(Component::class);
});

it('converts strings to Placeholders', function () {
    $keys = ['title'];
    $schema = ($this->getAuditSchema)($this->values, $keys);

    /** @var ?Placeholder $title */
    $title = array_pop($schema);

    expect($title)->toBeInstanceOf(Placeholder::class)
        ->and($title->getName())->toBe('title')
        ->and($title->getContent())->toBeScalar()->toBe('I am a title!');

});

it('converts integers to Placeholders', function () {
    $keys = ['value'];

    $schema = ($this->getAuditSchema)($this->values, $keys);

    /** @var ?Placeholder $value */
    $value = array_pop($schema);

    expect($value)->toBeInstanceOf(Placeholder::class)
        ->and($value->getName())->toBe('value')
        ->and($value->getContent())->toBeScalar()->toBe(123);

});

it('converts non-scalars to Text Areas', function () {
    $keys = ['json'];

    $schema = ($this->getAuditSchema)($this->values, $keys);

    /** @var ?Textarea $json */
    $json = array_pop($schema);

    expect($json)->toBeInstanceOf(Textarea::class)
        ->and($json->getName())->toBe('json')
        ->and($json->isReadOnly())->toBeTrue()
        ->and($json->isDehydrated())->toBeFalse()
        ->and($json->getRows())->toBe(16);

});
