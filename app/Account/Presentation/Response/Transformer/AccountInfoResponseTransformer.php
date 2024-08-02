<?php

declare(strict_types=1);

namespace App\Account\Presentation\Response\Transformer;

use App\Account\Domain\Account;
use App\Account\Presentation\Response\DTO\AccountInfoResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<Account, AccountInfoResponseDTO>
 */
final readonly class AccountInfoResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): AccountInfoResponseDTO
    {
        return new AccountInfoResponseDTO(
            id: $entry->id,
            login: $entry->login,
        );
    }
}
