<?php

declare(strict_types=1);


require_once __DIR__ . '/vendor/autoload.php';

$dbParams = require __DIR__ . '/migrations-db.php';
$connection = \Doctrine\DBAL\DriverManager::getConnection($dbParams);

return \Doctrine\Migrations\DependencyFactory::fromConnection(
    new \Doctrine\Migrations\Configuration\Migration\PhpFile(__DIR__ . '/migrations.php'),
    new \Doctrine\Migrations\Configuration\Connection\ExistingConnection($connection)
); 