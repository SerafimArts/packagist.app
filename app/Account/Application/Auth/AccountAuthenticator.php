<?php

declare(strict_types=1);

namespace App\Account\Application\Auth;

use App\Account\Application\Auth\Exception\InvalidCredentialsException;
use App\Account\Domain\AccountCredentialsFinder;
use App\Account\Domain\Token\Token;
use App\Account\Domain\Token\TokenCreator;
use App\Shared\Domain\DomainException;

final readonly class AccountAuthenticator
{
    public function __construct(
        private TokenCreator $tokens,
        private AccountCredentialsFinder $accounts,
    ) {}

    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     *
     * @throws InvalidCredentialsException
     * @throws \Throwable
     */
    public function login(string $login, #[\SensitiveParameter] string $password): Token
    {
        try {
            $account = $this->accounts->getByCredentials($login, $password);
        } catch (DomainException $e) {
            throw InvalidCredentialsException::becauseInvalidCredentials($login, $e);
        }

        return $this->tokens->create($account);
    }
}
