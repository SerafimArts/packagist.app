<?php

declare(strict_types=1);

namespace App\Package\Application\Command;

use App\Shared\Domain\Id\AccountId;
use Psr\Http\Message\UriInterface;

abstract readonly class CreateUriPackageCommand extends CreatePackageCommand
{
    public function __construct(
        AccountId $owner,
        public UriInterface $uri,
    ) {
        parent::__construct($owner);
    }
}
