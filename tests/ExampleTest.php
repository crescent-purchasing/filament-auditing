<?php

use CrescentPurchasing\FilamentAuditing\Audit;
use Illuminate\Support\Str;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('uses translations', function () {
    expect(__('filament-auditing::resource.table.columns.created_at'))->toBeString()->toBe('Recorded at');
});

it('can use attributes', function () {
    $model = new Audit;

    $type = Audit::class;
    $id = 1;

    $model->auditable_type = $type;
    $model->auditable_id = $id;

    $formattedType = Str::of($type)->classBasename()->headline()->toString();

    expect($model->name)
        ->toBeString()
        ->toBe(__('filament-auditing::model.name', [
            'id' => $id,
            'type' => $formattedType,
        ]));
});
