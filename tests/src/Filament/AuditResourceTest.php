<?php

use CrescentPurchasing\FilamentAuditing\Filament\AuditResource;

it('can render resource', function () {
    test()->actingAs(test()->admin);
    test()->get(AuditResource::getUrl())->assertSuccessful();
});
