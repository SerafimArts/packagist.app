<?php

declare(strict_types=1);

namespace App\Package\Domain\Version\Reference;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class SourceReference extends Reference
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     * @param non-empty-string $hash
     */
    public function __construct(
        string $type,
        string $url,
        #[ORM\Column(type: 'string', options: ['default' => ''])]
        public string $hash,
    ) {
        parent::__construct($type, $url);
    }

    public function isValid(): bool
    {
        return parent::isValid() && $this->hash !== '';
    }

    public static function createEmpty(): self
    {
        // @phpstan-ignore-next-line
        return new self('', '', '');
    }
}
