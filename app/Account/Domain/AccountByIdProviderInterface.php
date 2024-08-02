<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Shared\Domain\Id\AccountId;

interface AccountByIdProviderInterface
{
    public function findById(AccountId $id): ?Account;
}
