# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/laravel-ahrefs/commits/master/compare/v1.0.0...master)

### Added

- Initial release.
- `Ahrefs` client covering `domainRating()`, `backlinks()`, `refDomains()`, `organicKeywords()`, `topPages()`, `keywordOverview()`, `keywordSuggestions()`, and `serpOverview()`.
- `Ahrefs` facade backed by a container singleton.
- Configurable token and base URL via `config/ahrefs.php`, with a fallback to `config('services.ahrefs.token')`.

## [v1.0.0](https://github.com/jeffersongoncalves/laravel-ahrefs/commits/master/compare/master...v1.0.0) - 2026-09-05

Initial release: Ahrefs API v3 client for Laravel covering domain rating, backlinks, referring domains, organic keywords, top pages, keyword overview, keyword suggestions, and SERP overview.
