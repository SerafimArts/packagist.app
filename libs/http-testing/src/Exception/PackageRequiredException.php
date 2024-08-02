<?php

declare(strict_types=1);

namespace Local\Testing\Http\Exception;

use PHPUnit\Framework\IncompleteTest;

final class PackageRequiredException extends \LogicException implements IncompleteTest
{
    public function __construct(string $package)
    {
        parent::__construct(\sprintf('"%s" package required', $package));
    }
}
