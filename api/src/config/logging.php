<?php

declare(strict_types=1);

return [
    'logs' => [
        'directory' => realpath(__DIR__ . '/../../logs'),
        'error_log' => 'error.log',
        'level' => \Monolog\Level::Debug,
    ]
];
