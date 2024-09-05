<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Version implements \Stringable
{
    /**
     * @var non-empty-string
     */
    public const string DEFAULT_VALUE = '0.0.1';

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        #[ORM\Column(name: 'version', type: 'string', options: ['default' => '0.0.1'])]
        public readonly string $value = self::DEFAULT_VALUE,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
