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
     * @return array<array-key, mixed> | false
     */
    public function getLeadByEmail(string $email): array | false
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('leads')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAssociative();
    }

    /**
     * @param array<array-key, mixed> $data
     * @return array<array-key, mixed> | false
     */
    public function createNewLead(array $data): array | false
    {
        $this->db->insert('leads', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'source' => $data['source'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $lastInsertId = (int) $this->db->lastInsertId();

        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('leads')
            ->where('id = :id')
            ->setParameter('id', $lastInsertId)
            ->executeQuery()
            ->fetchAssociative();
    }
}
