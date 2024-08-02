<?php

declare(strict_types=1);

namespace App\Account\Domain;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class Authentication implements
    UserInterface,
    EquatableInterface,
    PasswordAuthenticatedUserInterface,
    \Stringable
{
    public function __construct(
        private Account $account,
    ) {}

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        $id = $this->account->id;

        return $user instanceof Account
            && $id->equals($user->id);
    }

    public function getPassword(): ?string
    {
        return $this->account->password->hash;
    }

    public function getRoles(): array
    {
        $result = [];

        foreach ($this->account->roles as $role) {
            $result[] = $role->getName();
        }

        return $result;
    }

    public function eraseCredentials(): void
    {
        throw new \BadMethodCallException('Credentials cannot be erased');
    }

    /**
     * @return non-empty-string
     */
    public function getUserIdentifier(): string
    {
        $id = $this->account->id;

        return $id->toString();
    }

    public function __toString(): string
    {
        return $this->account->login;
    }
}
