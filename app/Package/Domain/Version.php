<?php

declare(strict_types=1);

namespace App\Package\Domain;

use Composer\Semver\VersionParser;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Version implements \Stringable
{
    private static ?VersionParser $parser = null;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    public string $normalized;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    public string $value {
        get => $value;
        set (string $value) {
            $this->normalized = (self::$parser ??= new VersionParser())
                ->normalize($value);
            $this->value = $value;
        }
    }

    /**
     * @param non-empty-string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
