<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Event\AccountVerified;
use App\Account\Domain\Exception\AccountNotFoundException;
use App\Account\Domain\Exception\AccountNotVerifiableException;
use App\Account\Domain\Exception\InvalidAccountCredentialsException;
use App\Shared\Domain\EventBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class AccountCredentialsFinder
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private AccountRepositoryInterface $accounts,
        private EventBusInterface $events,
    ) {}

    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     */
    public function getByCredentials(string $login, #[\SensitiveParameter] string $password): Account
    {
        $account = $this->accounts->findByLogin($login);

        if ($account === null) {
            throw AccountNotFoundException::becauseInvalidLogin($login);
        }

        if (!$account->password->isPasswordProtected()) {
            throw AccountNotVerifiableException::becausePasswordIsEmpty($login);
        }

        $authentication = new Authentication($account);

        if (!$this->hasher->isPasswordValid($authentication, $password)) {
            throw InvalidAccountCredentialsException::becauseInvalidPassword($login);
        }

        $this->events->dispatch(new AccountVerified(
            verifiedLogin: $login,
            verifiedPassword: $password,
            account: $account,
        ));

        return $account;
    }
}
