<?php

use CrescentPurchasing\FilamentAuditing\Actions\GetUser;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\UserResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use OwenIt\Auditing\Models\Audit;

beforeEach(function () {
    $this->getUser = new GetUser;

    test()->actingAs(test()->admin);

    Article::factory()->create();

    $this->audit = Audit::latest('id')->firstOrFail();
});

it('can get user of Audit', function () {
    expect(($this->getUser)($this->audit))
        ->toBeInstanceOf(User::class)
        ->email->toBe(test()->admin->email);
});

it('can get user of User', function () {
    expect(($this->getUser)(test()->admin))
        ->toBeInstanceOf(User::class)
        ->email->toBe(test()->admin->email);
});

it('returns null when Audit has no user', function () {
    $this->app['auth']->guard()->logout();

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    expect(($this->getUser)($audit))
        ->toBeNull();
});

it('can get icon of user', function () {
    $userIcon = UserResource::getNavigationIcon();
    $auditUserIcon = $this->getUser->icon($this->audit);

    expect($auditUserIcon)->toBe($userIcon);
});

it('can get title of user', function () {
    $userTitle = UserResource::getRecordTitle(test()->admin);
    $auditUserTitle = $this->getUser->title($this->audit);

    expect($auditUserTitle)->toBe($userTitle);
});

it('can get url of user', function () {
    $userUrl = UserResource::getGlobalSearchResultUrl(test()->admin);
    $auditUserUrl = $this->getUser->url($this->audit);

    expect($auditUserUrl)->toBe($userUrl);
});

it('can get visibility of user', function () {
    $visibility = $this->getUser->visibility($this->audit);

    expect($visibility)->toBeTrue();
});
