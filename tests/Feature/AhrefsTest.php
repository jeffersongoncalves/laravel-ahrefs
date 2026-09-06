<?php

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Ahrefs\Ahrefs;
use JeffersonGoncalves\Ahrefs\Facades\Ahrefs as AhrefsFacade;

it('fetches the domain rating', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/domain-rating*' => Http::response(['domain_rating' => 74.2], 200),
    ]);

    expect(app(Ahrefs::class)->domainRating('example.com'))->toBe(['domain_rating' => 74.2]);

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.ahrefs.com/v3/site-explorer/domain-rating?target=example.com'
        && $request->hasHeader('Authorization', 'Bearer fake-token'));
});

it('fetches backlinks with mode and limit', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/backlinks*' => Http::response(['backlinks' => []], 200),
    ]);

    app(Ahrefs::class)->backlinks('example.com', 'subdomains', 50);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), 'target=example.com')
        && str_contains($request->url(), 'mode=subdomains')
        && str_contains($request->url(), 'limit=50'));
});

it('omits null optional params from the backlinks query', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/backlinks*' => Http::response(['backlinks' => []], 200),
    ]);

    app(Ahrefs::class)->backlinks('example.com');

    Http::assertSent(fn (Request $request) => ! str_contains($request->url(), 'mode=')
        && ! str_contains($request->url(), 'limit='));
});

it('fetches referring domains', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/refdomains*' => Http::response(['refdomains' => []], 200),
    ]);

    app(Ahrefs::class)->refDomains('example.com', 'domain', 10);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/site-explorer/refdomains')
        && str_contains($request->url(), 'mode=domain')
        && str_contains($request->url(), 'limit=10'));
});

it('fetches organic keywords', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/organic-keywords*' => Http::response(['keywords' => []], 200),
    ]);

    app(Ahrefs::class)->organicKeywords('example.com', 'exact', 'us', 20);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/site-explorer/organic-keywords')
        && str_contains($request->url(), 'mode=exact')
        && str_contains($request->url(), 'country=us')
        && str_contains($request->url(), 'limit=20'));
});

it('fetches top pages', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/top-pages*' => Http::response(['pages' => []], 200),
    ]);

    app(Ahrefs::class)->topPages('example.com', 'prefix', 'br');

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/site-explorer/top-pages')
        && str_contains($request->url(), 'mode=prefix')
        && str_contains($request->url(), 'country=br')
        && ! str_contains($request->url(), 'limit='));
});

it('fetches a keyword overview from an array of keywords', function () {
    Http::fake([
        'api.ahrefs.com/v3/keywords-explorer/overview*' => Http::response(['keywords' => []], 200),
    ]);

    app(Ahrefs::class)->keywordOverview(['laravel', 'php'], 'us');

    Http::assertSent(fn (Request $request) => str_contains(urldecode($request->url()), 'keywords=laravel,php')
        && str_contains($request->url(), 'country=us'));
});

it('fetches a keyword overview from a csv string', function () {
    Http::fake([
        'api.ahrefs.com/v3/keywords-explorer/overview*' => Http::response(['keywords' => []], 200),
    ]);

    app(Ahrefs::class)->keywordOverview('laravel,php');

    Http::assertSent(fn (Request $request) => str_contains(urldecode($request->url()), 'keywords=laravel,php'));
});

it('fetches keyword suggestions', function () {
    Http::fake([
        'api.ahrefs.com/v3/keywords-explorer/matching-terms*' => Http::response(['terms' => []], 200),
    ]);

    app(Ahrefs::class)->keywordSuggestions('laravel', 'us', 25);

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/keywords-explorer/matching-terms')
        && str_contains($request->url(), 'keyword=laravel')
        && str_contains($request->url(), 'limit=25'));
});

it('fetches a SERP overview', function () {
    Http::fake([
        'api.ahrefs.com/v3/keywords-explorer/serp-overview*' => Http::response(['serp' => []], 200),
    ]);

    app(Ahrefs::class)->serpOverview('laravel', 'us');

    Http::assertSent(fn (Request $request) => str_contains($request->url(), '/keywords-explorer/serp-overview')
        && str_contains($request->url(), 'country=us'));
});

it('throws on a non-2xx response', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/domain-rating*' => Http::response(['error' => 'unauthorized'], 401),
    ]);

    expect(fn () => app(Ahrefs::class)->domainRating('example.com'))
        ->toThrow(RequestException::class);
});

it('resolves the facade to the Ahrefs client', function () {
    expect(AhrefsFacade::getFacadeRoot())->toBeInstanceOf(Ahrefs::class);
});

it('falls back to the services.ahrefs.token config value', function () {
    config()->set('ahrefs.token', null);
    config()->set('services.ahrefs.token', 'services-token');

    Http::fake([
        'api.ahrefs.com/v3/site-explorer/domain-rating*' => Http::response(['domain_rating' => 1], 200),
    ]);

    app(Ahrefs::class)->domainRating('example.com');

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer services-token'));
});
