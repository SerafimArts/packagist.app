<?php

declare(strict_types=1);

namespace App\Account\Application\Auth;

use App\Account\Application\Auth\Exception\AuthenticationFailedException;
use App\Account\Domain\AccountCredentialsFinder;
use App\Account\Domain\Token\Token;
use App\Account\Domain\Token\TokenCreator;
use App\Shared\Domain\DomainException;

final readonly class AuthenticationProcess
{
    public function __construct(
        private TokenCreator $tokens,
        private AccountCredentialsFinder $accounts,
    ) {}

    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     *
     * @throws AuthenticationFailedException
     */
    public function login(string $login, #[\SensitiveParameter] string $password): Token
    {
        try {
            $account = $this->accounts->getByCredentials($login, $password);
        } catch (DomainException $e) {
            throw AuthenticationFailedException::becauseInvalidCredentials($login, $e);
        }

        return $this->tokens->create($account);
    }
}
