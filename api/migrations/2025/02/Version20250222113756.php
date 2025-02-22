<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222113756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create leads table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('leads');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
            'unsigned' => true,
        ]);
        $table->addColumn('name', 'string', [
            'length' => 50,
            'notnull' => true,
        ]);
        $table->addColumn('email', 'string', [
            'length' => 100,
            'notnull' => true,
        ]);
        $table->addColumn('phone', 'string', [
            'length' => 20,
            'notnull' => false,
        ]);
        $table->addColumn('source', 'string', [
            'columnDefinition' => "ENUM('facebook', 'google', 'linkedin', 'manual')",
            'notnull' => true,
        ]);
        $table->addColumn('created_at', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email'], 'uniq_leads_email');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('leads');
    }
}
