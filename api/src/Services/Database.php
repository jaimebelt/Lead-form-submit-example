<?php

declare(strict_types=1);

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private Connection $connection;

    public function __construct(array $connectionParams)
    {
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
} 