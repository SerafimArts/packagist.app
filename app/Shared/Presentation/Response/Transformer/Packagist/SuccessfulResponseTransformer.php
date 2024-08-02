<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Transformer\Packagist;

use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @api
 *
 * @template TInput of mixed
 * @template-extends ResponseTransformer<TInput, TInput>
 */
final readonly class SuccessfulResponseTransformer extends ResponseTransformer
{
    /**
     * @param TInput $entry
     *
     * @return TInput
     */
    public function transform(mixed $entry): mixed
    {
        return $entry;
    }
}
