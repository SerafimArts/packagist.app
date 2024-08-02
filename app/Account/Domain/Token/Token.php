<?php

declare(strict_types=1);

namespace App\Account\Domain\Token;

use App\Account\Domain\Account;

final readonly class Token extends EncryptedToken
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        string $value,
        private Account $account,
    ) {
        parent::__construct($value);
    }

    public function getAccount(): Account
    {
        return $this->account;
    }
}
