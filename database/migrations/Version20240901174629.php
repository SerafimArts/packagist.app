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
final class Version20240901174629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add downloads statistic table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE statistic_downloads (
                id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL,
                ip VARCHAR(255) NOT NULL,
                composer_version VARCHAR(255) DEFAULT NULL,
                php_version VARCHAR(255) DEFAULT NULL,
                os VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY(id)
            )
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE statistic_downloads
            SQL);
    }
}