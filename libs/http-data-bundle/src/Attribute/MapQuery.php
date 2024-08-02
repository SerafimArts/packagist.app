<?php

declare(strict_types=1);

namespace Local\HttpData\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class MapQuery extends RequestDTOAttribute {}
