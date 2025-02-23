<?php

declare(strict_types=1);

return [
    'cors' => [
        'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? 'http://localhost:4200'),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
        'allowed_headers' => ['X-Requested-With', 'Content-Type', 'Accept', 'Origin', 'Authorization'],
    ]
];
