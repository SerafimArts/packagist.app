<?php

declare(strict_types=1);

namespace Local\Testing\Http\Extension;

use Local\Testing\Http\Exception\PackageRequiredException;
use Local\Testing\Http\TestingResponse;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @mixin TestingResponse
 */
trait VarDumperExtension
{
    private function assertVarDumperSupports(): void
    {
        if (!\class_exists(VarDumper::class)) {
            throw new PackageRequiredException('symfony/var-dumper');
        }
    }

    public function dump(): self
    {
        $this->assertVarDumperSupports();

        VarDumper::dump($this->response->getContent());

        \flush();

        return $this;
    }
}
