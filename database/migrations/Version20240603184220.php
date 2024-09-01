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
final class Version20240603184220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create accounts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TYPE account_role AS ENUM(
                'ROLE_SUPER_ADMIN',
                'ROLE_ADMIN'
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE accounts (
                id UUID NOT NULL,
                login VARCHAR(255) NOT NULL CHECK(login <> ''),
                password VARCHAR(255) DEFAULT NULL,
                roles account_role[] DEFAULT '{}' NOT NULL,
                created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
            SQL);

        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX login_unique ON accounts (login)
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE accounts
            SQL);

        $this->addSql(<<<'SQL'
            DROP TYPE account_role
            SQL);
    }
}
