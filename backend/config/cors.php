<?php

return [
    'paths' => ['api/*', 'storage/*', 'sanctum/csrf-cookie', 'admin/login', 'admin/logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [],
    'allowed_origins_patterns' => ['/^http:\/\/localhost:808[0-9]$/'],

    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
