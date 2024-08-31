<?php

declare(strict_types=1);

namespace Local\Integration;

interface ClientInterface extends
    AuthUriProviderInterface,
    AccountProviderInterface {}
