<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Exception;

/**
 * @template TData of mixed
 */
interface PublicDataProviderInterface
{
    /**
     * @return TData
     */
    public function getPublicData(): mixed;
}
