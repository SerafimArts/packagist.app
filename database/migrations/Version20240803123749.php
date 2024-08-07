<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240803123749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create simple package versions table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE package_versions (
                id UUID NOT NULL,
                package_id UUID DEFAULT NULL,
                version VARCHAR(255) NOT NULL DEFAULT '0.0.1',
                description TEXT DEFAULT NULL,
                license VARCHAR(255)[] DEFAULT '{}' NOT NULL,
                source_type VARCHAR(255) NOT NULL DEFAULT '',
                source_url VARCHAR(255) NOT NULL DEFAULT '',
                source_hash VARCHAR(255) NOT NULL DEFAULT '',
                dist_type VARCHAR(255) NOT NULL DEFAULT '',
                dist_url VARCHAR(255) NOT NULL DEFAULT '',
                dist_hash VARCHAR(255) DEFAULT NULL,
                is_release BOOLEAN NOT NULL DEFAULT false,
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FD5DD4BCF44CABFF ON package_versions (package_id)
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE package_versions ADD CONSTRAINT FK_FD5DD4BCF44CABFF
                FOREIGN KEY (package_id) REFERENCES packages (id)
                    NOT DEFERRABLE INITIALLY IMMEDIATE
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE package_versions DROP CONSTRAINT FK_FD5DD4BCF44CABFF
            SQL);

        $this->addSql(<<<'SQL'
            DROP TABLE package_versions
            SQL);
    }
}
