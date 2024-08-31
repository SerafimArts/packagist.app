<?php

declare(strict_types=1);

namespace Local\Integration\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('local/integration')]
abstract class TestCase extends BaseTestCase {}
