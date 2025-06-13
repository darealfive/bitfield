<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Bit.php';

trait DataproviderTrait
{
    public static function dataproviderIntegersExclusiveZero(): array
    {
        return self::integers(1);
    }

    public static function dataproviderIntegersInclusiveZero(): array
    {
        return self::integers(0);
    }

    private static function integers(int $start): array
    {
        return [
            ...array_map(static fn(int $value): array => [$value], range($start, 15)),
            ...array_map(static fn(int $value): array => [$value], range(PHP_INT_MAX - 15, PHP_INT_MAX))
        ];
    }
}