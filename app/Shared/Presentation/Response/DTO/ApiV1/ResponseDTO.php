<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\DTO\ApiV1;

/**
 * @template TData of mixed
 */
abstract readonly class ResponseDTO
{
    /**
     * @param TData|null $data
     */
    public function __construct(
        public mixed $data = null,
    ) {}
}
