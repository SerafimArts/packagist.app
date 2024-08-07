<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Persistence\Repository;

use App\Account\Domain\Account;
use App\Account\Domain\AccountId;
use App\Account\Domain\AccountRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Repository\DatabaseRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Infrastructure\Persistence\Repository
 *
 * @template-extends DatabaseRepository<Account>
 */
final class DatabaseAccountRepository extends DatabaseRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findByLogin(string $login): ?Account
    {
        return $this->findOneBy(['login' => $login]);
    }

    public function findById(AccountId $id): ?Account
    {
        return $this->find($id->toString());
    }
}
