<?php

declare(strict_types=1);

namespace Local\Token\Reader\SymfonyRequest;

use Local\Token\Reader\TokenReaderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @template-extends TokenReaderInterface<Request>
 */
interface SymfonyRequestTokenReaderInterface extends TokenReaderInterface {}
