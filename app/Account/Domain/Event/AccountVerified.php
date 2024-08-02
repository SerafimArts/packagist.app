<?php

declare(strict_types=1);

namespace App\Account\Domain\Event;

use App\Account\Domain\Account;

final readonly class AccountVerified
{
    /**
     * @param non-empty-string $verifiedLogin
     * @param non-empty-string $verifiedPassword
     */
    public function __construct(
        public string $verifiedLogin,
        #[\SensitiveParameter]
        public string $verifiedPassword,
        public Account $account,
    ) {}
}
