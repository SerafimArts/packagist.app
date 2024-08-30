<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\DTO;

abstract readonly class ReferenceResponseDTO
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     */
    public function __construct(
        public string $type,
        public string $url,
    ) {}
}
