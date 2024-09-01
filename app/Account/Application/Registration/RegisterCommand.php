<?php

declare(strict_types=1);

namespace App\Account\Application\Registration;

final readonly class RegisterCommand
{
    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     */
    public function __construct(
        public string $login,
        #[\SensitiveParameter]
        public string $password,
    ) {}
}
