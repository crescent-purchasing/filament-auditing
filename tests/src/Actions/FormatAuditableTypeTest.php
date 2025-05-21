<?php

use CrescentPurchasing\FilamentAuditing\Actions\FormatAuditableType;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use Illuminate\Support\Str;

it('formats auditable types', function () {
    $type = Article::class;

    $formatAuditableType = new FormatAuditableType;

    $formattedType = Str::of($type)
        ->classBasename()
        ->headline()
        ->toString();

    expect($formatAuditableType($type))->toBeString()->toEqual($formattedType);
});

it('uses custom auditable type formatting', function () {
    $type = Article::class;

    $returnString = 'custom type!';

    $formatClosure = fn (string $value): string => $returnString;

    FilamentAuditingPlugin::get()->formatAuditableTypeUsing($formatClosure);

    $formatAuditableType = new FormatAuditableType;

    expect($formatAuditableType($type))
        ->toBeString()
        ->toEqual($formatClosure($type))
        ->toEqual($returnString);
});
