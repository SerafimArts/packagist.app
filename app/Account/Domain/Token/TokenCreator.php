<?php

declare(strict_types=1);

namespace App\Account\Domain\Token;

use App\Account\Domain\Account;
use Local\Token\TokenBuilderInterface;

final readonly class TokenCreator
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_EXPIRATION_INTERVAL = 'P1D';

    /**
     * @param TokenBuilderInterface<array<non-empty-string, mixed>> $builder
     * @param non-empty-string $tokenAccountIdKey
     */
    public function __construct(
        private string $tokenAccountIdKey,
        private TokenBuilderInterface $builder,
        private mixed $expiresAt = self::DEFAULT_EXPIRATION_INTERVAL,
    ) {}

    public function create(Account $account): Token
    {
        return new Token(
            value: $this->builder->build(
                payload: [
                    $this->tokenAccountIdKey => $account->id->toString(),
                ],
                expiresAt: $this->expiresAt,
            ),
            account: $account,
        );
    }
}
