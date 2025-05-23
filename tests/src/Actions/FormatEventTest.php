<?php

use CrescentPurchasing\FilamentAuditing\Actions\FormatEvent;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Illuminate\Support\Str;

it('formats events', function () {
    $event = 'updated';

    $formatEvent = new FormatEvent;

    $formattedEvent = Str::of($event)
        ->headline()
        ->toString();

    expect($formatEvent($event))->toBeString()->toEqual($formattedEvent);
});

it('uses custom event formatting', function () {
    $event = 'updated';

    $returnString = 'custom event!';

    $formatClosure = fn (string $value): string => $returnString;

    FilamentAuditingPlugin::get()->formatEventUsing($formatClosure);

    $formatEvent = new FormatEvent;

    expect($formatEvent($event))
        ->toBeString()
        ->toEqual($formatClosure($event))
        ->toEqual($returnString);
});
