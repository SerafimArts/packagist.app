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
        if ($errors = $this->validator->getPackageError($package)) {
            throw InvalidCredentialsException::fromInvalidPackage($package, $errors);
        }

        [$vendor, $name] = \explode('/', $package);

        return new Credentials($vendor, $name);
    }
}
