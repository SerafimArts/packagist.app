<?php

declare(strict_types=1);

namespace App\Package\Application\Command;

use App\Shared\Domain\Id\AccountId;

abstract readonly class CreatePackageCommand
{
    public function __construct(
        public AccountId $owner,
    ) {}
}
