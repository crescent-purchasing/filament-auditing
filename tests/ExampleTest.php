<?php

it('can test', function () {
    expect(true)->toBeTrue();
});

it('uses translations', function () {
    expect(__('filament-auditing::resource.fields.created_at'))->toBeString()->toBe('Recorded at');
});
