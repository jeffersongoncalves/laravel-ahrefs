## Laravel Ahrefs

### Overview

A lightweight [Ahrefs API v3](https://api.ahrefs.com/v3) client for Laravel. It
wraps the Site Explorer and Keywords Explorer endpoints behind a small
`Ahrefs` client/facade, threads your Bearer token, and returns the decoded
JSON body as a plain array — no DTOs, no response wrapper classes.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-ahrefs
</code-snippet>
@endverbatim

### Features

- **Domain Rating**: `domainRating(string $target)`.
- **Backlinks / Referring domains**: `backlinks()` and `refDomains()`, both accepting an optional `mode` (`domain`, `subdomains`, `prefix`, `exact`) and `limit`.
- **Organic keywords / Top pages**: `organicKeywords()` and `topPages()`, additionally accepting an optional `country`.
- **Keyword research**: `keywordOverview()`, `keywordSuggestions()`, and `serpOverview()`.

@verbatim
<code-snippet name="Fetch domain rating and backlinks" lang="php">
use JeffersonGoncalves\Ahrefs\Facades\Ahrefs;

$rating = Ahrefs::domainRating('example.com');
$backlinks = Ahrefs::backlinks('example.com', mode: 'subdomains', limit: 50);
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/ahrefs.php
return [
    'token' => env('AHREFS_API_KEY'),
    'base_url' => env('AHREFS_BASE_URL', 'https://api.ahrefs.com/v3'),
];
</code-snippet>
@endverbatim

### Best Practices

- Always catch `Illuminate\Http\Client\RequestException` around calls — a non-2xx response throws rather than returning an empty array.
- Pass `mode`/`country`/`limit` as `null` (the default) to omit them from the query string entirely; the client never sends a literal `null`.
- Prefer the `Ahrefs` facade in application code; resolve `JeffersonGoncalves\Ahrefs\Ahrefs::class` directly only when you need to swap the implementation in tests.
