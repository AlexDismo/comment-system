<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*', 'public/*', 'images/*', 'storage/images/*'],
    'allowed_methods' => ['GET'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
