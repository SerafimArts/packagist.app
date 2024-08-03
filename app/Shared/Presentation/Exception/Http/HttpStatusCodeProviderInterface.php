<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Exception\Http;

interface HttpStatusCodeProviderInterface
{
    /**
     * @return int<100, 599>
     */
    public function getHttpStatusCode(): int;
}
