<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Request\Transformer;

use App\Shared\Infrastructure\Transformer\Transformer;

/**
 * @template TInput of mixed
 * @template TOutput of mixed
 * @template-extends Transformer<TInput, TOutput>
 */
abstract readonly class RequestTransformer extends Transformer {}
