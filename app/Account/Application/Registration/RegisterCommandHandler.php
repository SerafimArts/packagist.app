<?php

declare(strict_types=1);

namespace App\Account\Application\Registration;

use App\Account\Domain\Token\Token;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler]
final readonly class RegisterCommandHandler
{
    public function __construct(
        private AccountRegistrar $registrar,
    ) {}

    public function __invoke(RegisterCommand $command): Token
    {
        return $this->registrar->register(
            login: $command->login,
            password: $command->password,
        );
    }
}
