<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Endpoint
    |--------------------------------------------------------------------------
    |
    | The Link Module API endpoint
    |
    */

    'endpoint' => env('LINK_MODULE_ENDPOINT', 'https://tsr-link-module.co.uk'),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | Verify SSL certificates via API calls. We do not recommend disabling
    | this for security reasons. This should only be adjusted when developing
    | locally using a self-signed SSL certificate.
    |
    */

    'verify_ssl' => env('LINK_MODULE_VERIFY_SSL', env('VERIFY_SSL', true)),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Credentials for Cognito authentication.
    |
    */

    'auth' => [
        'enabled' => env('LINK_MODULE_AUTH_ENABLED', true),
        'token_url' => env('LINK_MODULE_AUTH_TOKEN_URL'),
        'client_id' => env('LINK_MODULE_AUTH_CLIENT_ID'),
        'client_secret' => env('LINK_MODULE_AUTH_CLIENT_SECRET'),

        'token_cache_key' => env(
            'LINK_MODULE_AUTH_TOKEN_CACHE_KEY',
            ((string) env('APP_NAME')) . ((string) env('APP_ENV')) . '-link-module-auth-token'
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Decryption
    |--------------------------------------------------------------------------
    |
    | Credentials to decrypt encrypted link module shortcodes.
    |
    */
    'decrypt' => [
        'enabled' => env('LINK_MODULE_DECRYPT_ENABLED', true),
        'cipher' => env('LINK_MODULE_DECRYPT_CIPHER', 'chacha20'),
        'key' => env('LINK_MODULE_DECRYPT_KEY', ''),
        'iv' => env('LINK_MODULE_DECRYPT_IV', ''),
    ],

];
