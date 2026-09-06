<?php

namespace JeffersonGoncalves\Ahrefs;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Thin client over the Ahrefs API v3 (https://api.ahrefs.com/v3). Every
 * method maps to a single endpoint and returns the decoded JSON body as an
 * array. A non-2xx response throws Illuminate\Http\Client\RequestException
 * via Http::throw().
 */
class Ahrefs
{
    /**
     * Domain Rating for a target domain.
     *
     * @return array<string, mixed>
     */
    public function domainRating(string $target): array
    {
        return $this->get('/site-explorer/domain-rating', [
            'target' => $target,
        ]);
    }

    /**
     * Backlinks pointing to a target.
     *
     * @param  'domain'|'subdomains'|'prefix'|'exact'|null  $mode
     * @return array<string, mixed>
     */
    public function backlinks(string $target, ?string $mode = null, ?int $limit = null): array
    {
        return $this->get('/site-explorer/backlinks', [
            'target' => $target,
            'mode' => $mode,
            'limit' => $limit,
        ]);
    }

    /**
     * Referring domains for a target.
     *
     * @param  'domain'|'subdomains'|'prefix'|'exact'|null  $mode
     * @return array<string, mixed>
     */
    public function refDomains(string $target, ?string $mode = null, ?int $limit = null): array
    {
        return $this->get('/site-explorer/refdomains', [
            'target' => $target,
            'mode' => $mode,
            'limit' => $limit,
        ]);
    }

    /**
     * Organic keywords a target ranks for.
     *
     * @param  'domain'|'subdomains'|'prefix'|'exact'|null  $mode
     * @return array<string, mixed>
     */
    public function organicKeywords(string $target, ?string $mode = null, ?string $country = null, ?int $limit = null): array
    {
        return $this->get('/site-explorer/organic-keywords', [
            'target' => $target,
            'mode' => $mode,
            'country' => $country,
            'limit' => $limit,
        ]);
    }

    /**
     * Top organic pages for a target.
     *
     * @param  'domain'|'subdomains'|'prefix'|'exact'|null  $mode
     * @return array<string, mixed>
     */
    public function topPages(string $target, ?string $mode = null, ?string $country = null, ?int $limit = null): array
    {
        return $this->get('/site-explorer/top-pages', [
            'target' => $target,
            'mode' => $mode,
            'country' => $country,
            'limit' => $limit,
        ]);
    }

    /**
     * Volume/difficulty/CPC overview for one or more keywords.
     *
     * @param  array<int, string>|string  $keywords
     * @return array<string, mixed>
     */
    public function keywordOverview(array|string $keywords, ?string $country = null): array
    {
        return $this->get('/keywords-explorer/overview', [
            'keywords' => is_array($keywords) ? implode(',', $keywords) : $keywords,
            'country' => $country,
        ]);
    }

    /**
     * Matching-terms keyword suggestions for a seed keyword.
     *
     * @return array<string, mixed>
     */
    public function keywordSuggestions(string $keyword, ?string $country = null, ?int $limit = null): array
    {
        return $this->get('/keywords-explorer/matching-terms', [
            'keyword' => $keyword,
            'country' => $country,
            'limit' => $limit,
        ]);
    }

    /**
     * SERP overview for a keyword.
     *
     * @return array<string, mixed>
     */
    public function serpOverview(string $keyword, ?string $country = null): array
    {
        return $this->get('/keywords-explorer/serp-overview', [
            'keyword' => $keyword,
            'country' => $country,
        ]);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
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

    private function token(): string
    {
        $token = config('ahrefs.token') ?? config('services.ahrefs.token');

        return is_string($token) ? $token : '';
    }

    private function baseUrl(): string
    {
        $baseUrl = config('ahrefs.base_url', 'https://api.ahrefs.com/v3');

        return is_string($baseUrl) && $baseUrl !== '' ? $baseUrl : 'https://api.ahrefs.com/v3';
    }
}
