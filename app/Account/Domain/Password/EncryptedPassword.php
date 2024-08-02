<?php

declare(strict_types=1);

namespace App\Account\Domain\Password;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EncryptedPassword
{
    /**
     * @var non-empty-string|null
     */
    #[ORM\Column(name: 'password', type: 'string', nullable: true)]
    public ?string $hash = null;

    /**
     * @param non-empty-string|null $hash
     */
    public function __construct(?string $hash = null)
    {
        $this->hash = $hash;
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @api
     */
    public function isPasswordProtected(): bool
    {
        return $this->hash !== null;
    }
}
