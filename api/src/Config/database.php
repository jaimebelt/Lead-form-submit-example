<?php

declare(strict_types=1);

return [
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'charset' => 'utf8mb4'
    ]
];
