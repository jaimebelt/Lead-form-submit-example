<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

class LeadRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    public function findAll(): array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('leads')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function create(array $data): void
    {
        $this->db->insert('leads', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
} 