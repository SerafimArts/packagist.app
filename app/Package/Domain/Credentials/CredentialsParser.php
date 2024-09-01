<?php

declare(strict_types=1);

namespace App\Package\Domain\Credentials;

use App\Package\Domain\Credentials;
use App\Package\Domain\Credentials\Exception\InvalidCredentialsException;

final readonly class CredentialsParser
{
    public function __construct(
        private CredentialsValidator $validator,
    ) {}

    public function createFromPackage(string $package): Credentials
    {
        if ($this->validator->getPackageError($package)) {
            return $this->createFromNonOwnedPackage($package);
        }

        [$vendor, $name] = \explode('/', $package);

        return new Credentials($name, $vendor);
    }

    public function createFromNonOwnedPackage(string $package): Credentials
    {
        if ($errors = $this->validator->getNameError($package)) {
            throw InvalidCredentialsException::fromInvalidPackage($package, $errors);
        }

        return new Credentials($package);
    }
}
