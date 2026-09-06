---
name: ahrefs-development
description: Build and work with the Laravel Ahrefs client - a thin wrapper over the Ahrefs API v3 (Site Explorer and Keywords Explorer endpoints)
---

# Ahrefs Development

## When to use this skill

Use this skill when:

- Adding a new Ahrefs API v3 endpoint to the `Ahrefs` client
- Adjusting how query parameters are built or filtered
- Writing tests for Ahrefs API interactions with `Http::fake()`
- Troubleshooting authentication or base URL configuration

## Core Concepts

### The `Ahrefs` client

`src/Ahrefs.php` is a single class with one public method per endpoint. Every
method builds a query array and delegates to a private `get()` helper that:

1. Resolves the base URL and Bearer token from config.
2. Strips any `null`-valued query parameters with `array_filter()` so optional
   params (`mode`, `country`, `limit`) are omitted from the URL instead of
   being sent as the literal string `"null"`.
3. Calls `$response->throw()` so a non-2xx response raises
   `Illuminate\Http\Client\RequestException` instead of failing silently.
4. Returns the decoded JSON body, defaulting to `[]` when the body isn't a
   JSON array.

```php
private function get(string $endpoint, array $query): array
{
    $response = Http::baseUrl($this->baseUrl())
        ->withToken($this->token())
        ->acceptJson()
        ->get($endpoint, array_filter($query, fn ($value) => $value !== null));

    $response->throw();

    $data = $response->json();

    return is_array($data) ? $data : [];
}
```

### Facade

`src/Facades/Ahrefs.php` proxies to a container singleton named `ahrefs`,
registered in `AhrefsServiceProvider::packageRegistered()`. There are no
constructor dependencies, so `app(Ahrefs::class)` also resolves a fresh
instance without extra bindings.

### Token resolution

```php
private function token(): string
{
    $token = config('ahrefs.token') ?? config('services.ahrefs.token');

    return is_string($token) ? $token : '';
}
```

`config('ahrefs.token')` reads `env('AHREFS_API_KEY')`. When that's null the
client falls back to `config('services.ahrefs.token')`, matching the pattern
used across other jeffersongoncalves API-client packages.

## Adding a New Endpoint

1. Add a public method to `src/Ahrefs.php` returning `array<string, mixed>`.
2. Build the query array with named keys matching the Ahrefs API param names;
   pass `null` for anything optional so `get()` strips it.
3. Add the method to the `@method static` block in `src/Facades/Ahrefs.php`.
4. Add a `Http::fake()` test in `tests/Feature/AhrefsTest.php` asserting both
   the endpoint path and the query string built from the params.

## Common Patterns

### Success test

```php
it('fetches the domain rating', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/domain-rating*' => Http::response(['domain_rating' => 74.2], 200),
    ]);

    expect(app(Ahrefs::class)->domainRating('example.com'))->toBe(['domain_rating' => 74.2]);
});
```

### Error test

```php
it('throws on a non-2xx response', function () {
    Http::fake([
        'api.ahrefs.com/v3/site-explorer/domain-rating*' => Http::response(['error' => 'unauthorized'], 401),
    ]);

    expect(fn () => app(Ahrefs::class)->domainRating('example.com'))
        ->toThrow(RequestException::class);
});
```

## Troubleshooting

### Error: query string contains `mode=` or `limit=` unexpectedly

**Cause**: An optional parameter was passed as an empty string instead of `null`.

**Solution**: Pass `null` (the parameter default) rather than `''` to omit it —
`array_filter()` in `get()` only strips `null`, not empty strings.

### Error: `RequestException` on every call in tests

**Cause**: `Http::preventStrayRequests()` (set in `tests/Pest.php`) blocks any
request that doesn't match a `Http::fake()` pattern.

**Solution**: Make sure the fake pattern matches the full request path,
including the `/v3` base path, e.g. `api.ahrefs.com/v3/site-explorer/backlinks*`.

## API Reference

| Method | Endpoint |
|--------|----------|
| `domainRating(string $target)` | `GET /site-explorer/domain-rating` |
| `backlinks(string $target, ?string $mode, ?int $limit)` | `GET /site-explorer/backlinks` |
| `refDomains(string $target, ?string $mode, ?int $limit)` | `GET /site-explorer/refdomains` |
| `organicKeywords(string $target, ?string $mode, ?string $country, ?int $limit)` | `GET /site-explorer/organic-keywords` |
| `topPages(string $target, ?string $mode, ?string $country, ?int $limit)` | `GET /site-explorer/top-pages` |
| `keywordOverview(array\|string $keywords, ?string $country)` | `GET /keywords-explorer/overview` |
| `keywordSuggestions(string $keyword, ?string $country, ?int $limit)` | `GET /keywords-explorer/matching-terms` |
| `serpOverview(string $keyword, ?string $country)` | `GET /keywords-explorer/serp-overview` |

**Returns**: `array<string, mixed>` for every method.

**Throws**: `Illuminate\Http\Client\RequestException` on any non-2xx response.
