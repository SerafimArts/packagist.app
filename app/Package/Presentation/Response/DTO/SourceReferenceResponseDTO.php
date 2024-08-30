<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\DTO;

use JMS\Serializer\Annotation\SerializedName;

final readonly class SourceReferenceResponseDTO extends ReferenceResponseDTO
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     * @param non-empty-string $hash
     */
    public function __construct(
        string $type,
        string $url,
        #[SerializedName(name: 'reference')]
        public string $hash,
    ) {
        parent::__construct($type, $url);
    }
}
