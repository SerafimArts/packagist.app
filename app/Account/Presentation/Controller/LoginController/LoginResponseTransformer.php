<?php

declare(strict_types=1);

namespace App\Account\Presentation\Controller\LoginController;

use App\Account\Domain\Token\Token;
use App\Account\Presentation\Response\Transformer\AccountInfoResponseTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Presentation\Controller
 *
 * @template-extends ResponseTransformer<Token, LoginResponseDTO>
 */
final readonly class LoginResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private AccountInfoResponseTransformer $accounts,
    ) {}

    public function transform(mixed $entry): LoginResponseDTO
    {
        return new LoginResponseDTO(
            token: $entry->getValue(),
            account: $this->accounts->transform(
                entry: $entry->getAccount(),
            ),
        );
    }
}
