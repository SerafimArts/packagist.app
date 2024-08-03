<?php

declare(strict_types=1);

namespace App\Package\Domain;

use App\Shared\Infrastructure\Collection\Set;

/**
 * @template-extends Set<PackageVersion>
 */
final class PackageVersionsSet extends Set {}
