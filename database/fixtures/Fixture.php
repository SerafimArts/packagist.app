<?php

declare(strict_types=1);

namespace App\Database\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture as BaseFixture;

abstract class Fixture extends BaseFixture
{
    private int $progress = 0;

    private float $time = 0.0;

    protected const array PROGRESS_CHARS = [
        '▢▢▢',
        '■▢▢',
        '▢■▢',
        '▢▢■',
        '▢▢▢',
        '▢▢■',
        '▢■▢',
        '■▢▢'
    ];

    protected function progressNext(string $suffix = ''): string
    {
        if ($this->time < \microtime(true) - 0.1) {
            $this->time = \microtime(true);
            $this->progress++;
        }

        $char = self::PROGRESS_CHARS[$this->progress % 8];

        return "\r     $char $suffix";
    }

    protected function json(string $pathname): array
    {
        $json = \file_get_contents($pathname);

        return \json_decode($json, true, flags: \JSON_THROW_ON_ERROR);
    }
}
