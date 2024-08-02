<?php

declare(strict_types=1);

namespace App\Account\Presentation\Request\DTO\Auth;

use Symfony\Component\Validator\Constraints\NotBlank;

abstract readonly class CredentialsRequestDTO
{
    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     */
    public function __construct(
        #[NotBlank(message: 'Login must not be empty')]
        public string $login,
        #[NotBlank(message: 'Password must not be empty')]
        #[\SensitiveParameter]
        public string $password,
    ) {}
}
