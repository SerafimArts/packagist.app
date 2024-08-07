<?php

declare(strict_types=1);

namespace App\Account\Domain;

interface AccountRepositoryInterface
{
    public function findById(AccountId $id): ?Account;

    /**
     * @param non-empty-string $login
     */
    public function findByLogin(string $login): ?Account;
}
