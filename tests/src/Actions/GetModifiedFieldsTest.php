<?php

use CrescentPurchasing\FilamentAuditing\Actions\GetModifiedFields;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

beforeEach(function () {
    $this->getModifiedFields = new GetModifiedFields;

    $this->article = Article::factory()->create([
        'title' => 'I have been created!',
    ]);

});

it('gets the new values in an audit', function () {
    $this->article->update([
        'title' => 'I have been updated!',
    ]);

    $audit = Audit::latest('id')->firstOrFail();

    $modified = ($this->getModifiedFields)($audit);

    expect($modified)->toHaveCount(1)->toMatchArray([
        'title' => 'I have been updated!',
    ]);
});

it('gets the old values in an audit', function () {
    $this->article->update([
        'title' => 'I have been updated!',
    ]);

    $audit = Audit::latest('id')->firstOrFail();

    $modified = ($this->getModifiedFields)($audit, true);

    expect($modified)->toHaveCount(1)->toMatchArray([
        'title' => 'I have been created!',
    ]);
});
