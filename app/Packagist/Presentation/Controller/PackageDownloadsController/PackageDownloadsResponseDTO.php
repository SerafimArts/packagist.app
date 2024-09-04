<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageDownloadsController;

use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 */
#[VirtualProperty(name: 'status', exp: '"success"')]
final readonly class PackageDownloadsResponseDTO {}
