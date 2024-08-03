<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Event\AccountCreated;
use App\Account\Domain\Password\Password;
use App\Shared\Domain\EventBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class AccountCreator
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private EventBusInterface $events,
    ) {}

    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     */
    public function create(string $login, string $password): Account
    {
        $account = new Account(
            login: $login,
            password: Password::empty(),
            roles: [Role::SuperAdmin],
        );

        $account->password = Password::createForAccount(
            hasher: $this->hasher,
            value: $password,
            account: $account,
        );

        $this->events->dispatch(new AccountCreated($account));

        return $account;
    }
}
