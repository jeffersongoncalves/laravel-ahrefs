<div class="filament-hidden">

![Laravel Ahrefs](https://raw.githubusercontent.com/jeffersongoncalves/laravel-ahrefs/master/art/jeffersongoncalves-laravel-ahrefs.png)

</div>

# Laravel Ahrefs

[![Tests](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/tests.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/tests.yml)
[![PHPStan](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/phpstan.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/phpstan.yml)
[![Code Style](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/jeffersongoncalves/laravel-ahrefs/actions/workflows/fix-php-code-style-issues.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-ahrefs.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-ahrefs)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-ahrefs.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-ahrefs)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-ahrefs.svg?style=flat-square)](LICENSE.md)

A lightweight [Ahrefs API v3](https://api.ahrefs.com/v3) client for Laravel. It wraps the Site Explorer and Keywords Explorer endpoints behind a small `Ahrefs` client/facade, threads your Bearer token, and returns the decoded JSON body — no DTOs, no response wrappers.

## Features

- **Domain Rating** — `domainRating()`
- **Backlinks** — `backlinks()`
- **Referring domains** — `refDomains()`
- **Organic keywords** — `organicKeywords()`
- **Top pages** — `topPages()`
- **Keyword overview** — `keywordOverview()`
- **Keyword suggestions** — `keywordSuggestions()`
- **SERP overview** — `serpOverview()`
- A non-2xx response throws `Illuminate\Http\Client\RequestException`
- Optional `mode`/`country`/`limit` params are omitted from the query when left `null`

## Installation

```bash
composer require jeffersongoncalves/laravel-ahrefs
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="ahrefs-config"
```

## Configuration

Add to your `.env`:

```env
AHREFS_API_KEY=your-ahrefs-api-token
```

Generate a token at [https://app.ahrefs.com/api/keys](https://app.ahrefs.com/api/keys).

### Config Options

```php
// config/ahrefs.php
return [
    'token' => env('AHREFS_API_KEY'),
    'base_url' => env('AHREFS_BASE_URL', 'https://api.ahrefs.com/v3'),
];
```

When `ahrefs.token` is null the client falls back to `config('services.ahrefs.token')`.

## Usage

Via the facade:

```php
use JeffersonGoncalves\Ahrefs\Facades\Ahrefs;

Ahrefs::domainRating('example.com');

Ahrefs::backlinks('example.com', mode: 'subdomains', limit: 50);

Ahrefs::refDomains('example.com', mode: 'domain', limit: 100);

Ahrefs::organicKeywords('example.com', mode: 'exact', country: 'us', limit: 20);

Ahrefs::topPages('example.com', mode: 'domain', country: 'br', limit: 10);

Ahrefs::keywordOverview(['laravel', 'php'], country: 'us');
// or a single keyword / comma-separated string
Ahrefs::keywordOverview('laravel', country: 'us');

Ahrefs::keywordSuggestions('laravel', country: 'us', limit: 25);

Ahrefs::serpOverview('laravel', country: 'us');
```

Or inject/resolve the underlying client:

```php
use JeffersonGoncalves\Ahrefs\Ahrefs;

$ahrefs = app(Ahrefs::class);
$ahrefs->domainRating('example.com');
```

### `mode` values

`domain` (default), `subdomains`, `prefix`, `exact` — accepted by `backlinks()`, `refDomains()`, `organicKeywords()`, and `topPages()`.

### Error handling

```php
use Illuminate\Http\Client\RequestException;

try {
    $rating = Ahrefs::domainRating('example.com');
} catch (RequestException $e) {
    // $e->response->status(), $e->response->json(), ...
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
