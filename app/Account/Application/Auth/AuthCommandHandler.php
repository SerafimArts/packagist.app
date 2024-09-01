<?php

declare(strict_types=1);

namespace App\Account\Application\Auth;

use App\Account\Domain\Token\Token;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AuthCommandHandler
{
    public function __construct(
        private AccountAuthenticator $authenticator,
    ) {}

    public function __invoke(AuthCommand $command): Token
    {
        return $this->authenticator->login(
            login: $command->login,
            password: $command->password,
        );
    }
}
