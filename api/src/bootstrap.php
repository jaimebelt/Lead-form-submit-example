<?php

declare(strict_types=1);

use App\Service\Database;
use DI\ContainerBuilder;
use Doctrine\DBAL\Driver\Mysqli\Connection;
use Psr\Container\ContainerInterface;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    Database::class => function (ContainerInterface $container) {
        $config = require __DIR__ . '/config/database.php';
        return new Database($config['database']);
    },

    Connection::class => function (ContainerInterface $container) {
        return $container->get(Database::class)->getConnection();
    }
]);

return $containerBuilder->build();
