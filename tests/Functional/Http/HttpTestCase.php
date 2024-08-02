<?php

declare(strict_types=1);

namespace App\Tests\Functional\Http;

use App\Tests\Functional\TestCase;
use Local\Testing\Http\InteractWithHttpClient;

abstract class HttpTestCase extends TestCase
{
    use InteractWithHttpClient;
}
