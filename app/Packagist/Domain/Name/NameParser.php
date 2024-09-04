<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Name;

use App\Packagist\Domain\Name;
use App\Packagist\Domain\Name\Exception\InvalidNameException;

final readonly class NameParser
{
    public function __construct(
        private NameValidator $validator,
    ) {}

    public function parse(string $name): Name
    {
        if ($this->validator->getPackageError($name)) {
            return $this->createFromNonOwnedPackage($name);
        }

        [$vendor, $name] = \explode('/', $name);

        return new Name($name, $vendor);
    }

    private function createFromNonOwnedPackage(string $name): Name
    {
        if ($errors = $this->validator->getNameError($name)) {
            throw InvalidNameException::fromInvalidPackage($name, $errors);
        }

        return new Name($name);
    }
}
