<?php

declare(strict_types=1);

namespace App\Account\Presentation\Response\DTO\Auth;

use App\Account\Presentation\Response\DTO\AccountInfoResponseDTO;

abstract readonly class TokenInfoResponseDTO
{
    /**
     * @param non-empty-string $token
     */
    public function __construct(
        public string $token,
        public AccountInfoResponseDTO $account,
    ) {}
}
