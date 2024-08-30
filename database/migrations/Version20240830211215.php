<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @api
 */
final class Version20240830211215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add account integrations table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE account_integrations (
                id UUID NOT NULL,
                account_id UUID DEFAULT NULL,
                dsn VARCHAR(255) NOT NULL CHECK(dsn <> ''),
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E5F67AEC9B6B5FBA ON account_integrations (account_id)
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE account_integrations ADD CONSTRAINT FK_E5F67AEC9B6B5FBA
                FOREIGN KEY (account_id) REFERENCES accounts (id)
                    NOT DEFERRABLE INITIALLY IMMEDIATE
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE account_integrations DROP CONSTRAINT FK_E5F67AEC9B6B5FBA
            SQL);

        $this->addSql(<<<'SQL'
            DROP TABLE account_integrations
            SQL);
    }
}
