<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Bit.php';

trait DataproviderTrait
{
    public static function dataproviderIntegers(): array
    {
        return [
            ...array_map(static fn(int $value): array => [$value], range(0, 15)),
            ...array_map(static fn(int $value): array => [$value], range(PHP_INT_MAX - 15, PHP_INT_MAX))
        ];
    }
}