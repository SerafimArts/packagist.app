<?php

declare(strict_types=1);

namespace App\Package\Domain\Credentials;

use App\Package\Domain\Credentials;

final class CredentialsParser
{
    public function createFromPackage(string $package): Credentials
    {
        dd($package);
    }
}
