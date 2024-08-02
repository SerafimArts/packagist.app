<?php

declare(strict_types=1);

namespace Local\Token\Driver\Lcobucci;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key as KeyInterface;
use Lcobucci\JWT\Signer\Key\InMemory;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Driver;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token\Driver
 */
abstract readonly class LcobucciDriver extends Driver
{
    protected KeyInterface $key;

    protected Signer $signer;

    /**
     * @param non-empty-string $key
     */
    public function __construct(
        #[\SensitiveParameter]
        string $key,
        AlgoInterface $algo = Algo::DEFAULT,
        ClockInterface $clock = new NativeClock(),
        #[\SensitiveParameter]
        string $passphrase = ''
    ) {
        $this->key = InMemory::plainText($key, $passphrase);
        $this->signer = $this->createSigner($algo);

        parent::__construct($algo, $clock);
    }

    protected function createSigner(AlgoInterface $algo): Signer
    {
        $factory = new SignerFactory();

        return $factory->create($algo);
    }
}
