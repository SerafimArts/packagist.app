<?php

declare(strict_types=1);

namespace App\Account\Presentation\Response\DTO;

use App\Shared\Domain\Id\AccountId;

final class AccountInfoResponseDTO
{
    /**
     * @param non-empty-string $login
     */
    public function __construct(
        public AccountId $id,
        public string $login,
    ) {}
}
