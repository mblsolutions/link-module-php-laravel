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

];