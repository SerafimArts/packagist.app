<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @api
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Database\Migrations
 */
final class Version20240803123749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create simple package versions table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE package_releases (
                id UUID NOT NULL,
                package_id UUID DEFAULT NULL,
                version VARCHAR(255) NOT NULL CHECK(version <> ''),
                version_normalized VARCHAR(255) NOT NULL CHECK(version_normalized <> ''),
                description TEXT DEFAULT NULL,
                license VARCHAR(255)[] DEFAULT '{}' NOT NULL,
                source_type VARCHAR(255) NOT NULL DEFAULT '',
                source_url VARCHAR(255) NOT NULL DEFAULT '',
                source_hash VARCHAR(255) NOT NULL DEFAULT '',
                dist_type VARCHAR(255) NOT NULL DEFAULT '',
                dist_url VARCHAR(255) NOT NULL DEFAULT '',
                dist_hash VARCHAR(255) DEFAULT NULL CHECK (dist_hash <> ''),
                is_release BOOLEAN NOT NULL DEFAULT false,
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id),
                CONSTRAINT package_version_source_or_dist_required CHECK (
                    ( source_type <> '' AND source_url <> '' AND source_hash <> '' ) OR
                    ( dist_type <> '' AND dist_url <> '' )
                )
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9C1770BFF44CABFF ON package_releases (package_id)
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE package_releases ADD CONSTRAINT FK_FD5DD4BCF44CABFF
                FOREIGN KEY (package_id) REFERENCES packages (id)
                    ON DELETE CASCADE
                    NOT DEFERRABLE INITIALLY IMMEDIATE
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE package_releases DROP CONSTRAINT FK_FD5DD4BCF44CABFF
            SQL);

        $this->addSql(<<<'SQL'
            DROP TABLE package_releases
            SQL);
    }
}
