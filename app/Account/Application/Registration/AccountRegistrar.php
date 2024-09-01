<?php

declare(strict_types=1);

namespace App\Account\Application\Registration;

use App\Account\Application\Registration\Exception\AccountAlreadyRegisteredException;
use App\Account\Domain\AccountCreator;
use App\Account\Domain\Token\Token;
use App\Account\Domain\Token\TokenCreator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AccountRegistrar
{
    public function __construct(
        private EntityManagerInterface $em,
        private TokenCreator $tokens,
        private AccountCreator $accounts,
    ) {}

    /**
     * @param non-empty-string $login
     * @param non-empty-string $password
     *
     * @throws AccountAlreadyRegisteredException
     * @throws \Throwable
     */
    public function register(string $login, #[\SensitiveParameter] string $password): Token
    {
        $account = $this->accounts->create($login, $password);

        $this->em->persist($account);

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw AccountAlreadyRegisteredException::becauseAccountAlreadyExists($login, $e);
        }

        return $this->tokens->create($account);
    }
}
