<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Response;

interface ResponseEncoderInterface
{
    /**
     * @var int<100, 599>
     */
    public const DEFAULT_HTTP_CODE = Response::HTTP_OK;

    /**
     * @param int<100, 599> $code
     */
    public function encode(mixed $data, int $code = self::DEFAULT_HTTP_CODE): Response;
}
