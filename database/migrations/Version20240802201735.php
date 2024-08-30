<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @api
 */
final class Version20240802201735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create simple packages table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE packages (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL CHECK(name <> ''),
                vendor VARCHAR(255) NOT NULL CHECK(vendor <> ''),
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX package_name_idx ON packages (name)
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE packages');
    }
}
