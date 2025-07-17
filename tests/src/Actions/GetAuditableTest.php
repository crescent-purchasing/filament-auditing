<?php

use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\ArticleResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;

beforeEach(function () {
    test()->actingAs(test()->admin);

    $this->getAuditable = new GetAuditable;

    $this->articleTitle = 'Example post title!';

    $this->article = Article::factory()->create([
        'title' => $this->articleTitle,
    ]);

    $this->audit = $this->article->audits()->first();

});

it('can get auditable of Audit', function () {
    expect(($this->getAuditable)($this->audit))
        ->toBeInstanceOf(Article::class)
        ->title->toBe($this->articleTitle);
});

it('can get auditable of auditable', function () {
    $auditable = ($this->getAuditable)($this->article);

    expect($auditable)->toBeInstanceOf(Article::class)
        ->and($auditable->title)->toBe($this->articleTitle);
});

it('can get icon of auditable', function () {
    $articleIcon = ArticleResource::getNavigationIcon();
    $auditableIcon = $this->getAuditable->icon($this->audit);

    expect($auditableIcon)->toBe($articleIcon);
});

it('can get title of auditable', function () {
    $articleTitle = ArticleResource::getRecordTitle($this->article);
    $auditableTitle = $this->getAuditable->title($this->audit);

    expect($auditableTitle)->toBe($articleTitle);
});

it('can get url of auditable', function () {
    $articleUrl = ArticleResource::getGlobalSearchResultUrl($this->article);
    $auditableUrl = $this->getAuditable->url($this->audit);

    expect($auditableUrl)->toBe($articleUrl);
});

it('can get visibility of auditable', function () {
    $visibility = $this->getAuditable->visibility($this->audit);

    expect($visibility)->toBeTrue();
});
