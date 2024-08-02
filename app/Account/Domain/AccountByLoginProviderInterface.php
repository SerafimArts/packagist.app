<?php

declare(strict_types=1);

namespace App\Account\Domain;

interface AccountByLoginProviderInterface
{
    /**
     * @param non-empty-string $login
     */
    public function findByLogin(string $login): ?Account;
}