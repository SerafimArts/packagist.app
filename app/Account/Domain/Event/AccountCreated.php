<?php

declare(strict_types=1);

namespace App\Account\Domain\Event;

use App\Account\Domain\Account;

final readonly class AccountCreated
{
    public function __construct(
        public Account $account,
    ) {}
}
