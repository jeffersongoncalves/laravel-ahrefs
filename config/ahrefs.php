<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ahrefs API Token
    |--------------------------------------------------------------------------
    |
    | The Bearer token used to authenticate every request against the Ahrefs
    | API v3. Generate one at https://app.ahrefs.com/api/keys.
    |
    | When this is null the client falls back to config('services.ahrefs.token').
    |
    */
    'token' => env('AHREFS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL every endpoint is resolved against.
    |
    */
    'base_url' => env('AHREFS_BASE_URL', 'https://api.ahrefs.com/v3'),
];
