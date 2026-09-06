<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Ahrefs\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn () => Http::preventStrayRequests())
    ->in('Feature');
