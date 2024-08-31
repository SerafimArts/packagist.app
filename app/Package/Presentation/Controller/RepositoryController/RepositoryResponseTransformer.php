<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\RepositoryController;

use App\Package\Application\Repository\RepositoryInfo;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Presentation\Controller
 *
 * @template-extends ResponseTransformer<RepositoryInfo, RepositoryResponseDTO>
 */
final readonly class RepositoryResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): RepositoryResponseDTO
    {
        return new RepositoryResponseDTO(
            metadataTemplateUrl: $this->formatTemplateUrl($entry->metadataTemplateUrl),
            listUrl: $this->formatUrl($entry->listUrl),
        );
    }

    private function formatUrl(string $url): string
    {
        return $url;
    }

    private function formatTemplateUrl(string $url): string
    {
        return \urldecode($url);
    }
}
