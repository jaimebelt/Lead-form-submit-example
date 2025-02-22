<?php

declare(strict_types=1);

use App\Services\Database;
use DI\ContainerBuilder;
use Doctrine\DBAL\Driver\Mysqli\Connection;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    // ... existing definitions ...

    Database::class => function () {
        $config = require __DIR__ . '/Config/database.php';
        return new Database($config['database']);
    },

    Connection::class => function (Database $database) {
        return $database->getConnection();
    }
]);

$container = $containerBuilder->build();
