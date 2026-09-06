<?php

namespace JeffersonGoncalves\Ahrefs\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> domainRating(string $target)
 * @method static array<string, mixed> backlinks(string $target, ?string $mode = null, ?int $limit = null)
 * @method static array<string, mixed> refDomains(string $target, ?string $mode = null, ?int $limit = null)
 * @method static array<string, mixed> organicKeywords(string $target, ?string $mode = null, ?string $country = null, ?int $limit = null)
 * @method static array<string, mixed> topPages(string $target, ?string $mode = null, ?string $country = null, ?int $limit = null)
 * @method static array<string, mixed> keywordOverview(array<int, string>|string $keywords, ?string $country = null)
 * @method static array<string, mixed> keywordSuggestions(string $keyword, ?string $country = null, ?int $limit = null)
 * @method static array<string, mixed> serpOverview(string $keyword, ?string $country = null)
 *
 * @see \JeffersonGoncalves\Ahrefs\Ahrefs
 */
class Ahrefs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ahrefs';
    }
}
