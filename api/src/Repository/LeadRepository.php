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

    /**
     * Find all leads
     * 
     * @return array<array-key, mixed>
     */
    public function getAllLeads(): array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('leads')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public function createNewLead(array $data): void
    {
        $this->db->insert('leads', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
