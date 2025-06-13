<?php
/**
 * FilterableTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

use Darealfive\Bitfield\filter\Bit as FilterableBit;
use Darealfive\Bitfield\filter\Exponent as FilterableExponent;
use Darealfive\Bitfield\filter\Filterable;
use Darealfive\Bitfield\filter\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterableTest covers interface methods of {@link Filterable}
 */
#[CoversClass(Filterable::class)]
#[CoversClass(FilterableBit::class)]
#[CoversClass(FilterableExponent::class)]
final class FilterableTest extends TestCase
{
    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsLow(Type $type, array $bits, array $data): void
    {
        ['bitsLow' => $bitsLow] = $data;
        $this->assertSame($bitsLow, FilterableBit::LOW->filter($type, ...$bits));
    }

    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsHigh(Type $type, array $bits, array $data): void
    {
        ['bitsHigh' => $bitsHigh] = $data;
        $this->assertSame($bitsHigh, FilterableBit::HIGH->filter($type, ...$bits));
    }

    #[DataProvider('dataproviderBitsFilterKey')]
    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterExponentsEven(Type $type, array $bits, array $data): void
    {
        ['bitsEven' => $bitsEven] = $data;
        $this->assertSame($bitsEven, FilterableExponent::EVEN->filter($type, ...$bits));
    }

    #[DataProvider('dataproviderBitsFilterKey')]
    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterExponentsOdd(Type $type, array $bits, array $data): void
    {
        ['bitsOdd' => $bitsOdd] = $data;
        $this->assertSame($bitsOdd, FilterableExponent::ODD->filter($type, ...$bits));
    }

    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsLowSameAsFilterExponentsEven(Type $type, array $bits, array $data): void
    {
        ['bitsLow' => $bitsLow, 'bitsEven' => $bitsEven] = $data;
        $this->assertSame($bitsLow, $bitsEven);
        $this->assertSame(
            FilterableBit::LOW->filter($type, ...$bits),
            FilterableExponent::EVEN->filter($type, ...$bits)
        );
    }

    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsHighSameAsFilterExponentsOdd(Type $type, array $bits, array $data): void
    {
        ['bitsHigh' => $bitsHigh, 'bitsOdd' => $bitsOdd] = $data;
        $this->assertSame($bitsHigh, $bitsOdd);
        $this->assertSame(
            FilterableBit::HIGH->filter($type, ...$bits),
            FilterableExponent::ODD->filter($type, ...$bits)
        );
    }

    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsLowNotSameAsFilterExponentsOdd(Type $type, array $bits, array $data): void
    {
        ['bitsLow' => $bitsLow, 'bitsOdd' => $bitsOdd] = $data;
        $this->assertNotSame($bitsLow, $bitsOdd);
        $this->assertNotSame(
            FilterableBit::LOW->filter($type, ...$bits),
            FilterableExponent::ODD->filter($type, ...$bits)
        );
    }

    #[DataProvider('dataproviderBitsFilterValue')]
    public function testFilterBitsHighNotSameAsFilterExponentsEven(Type $type, array $bits, array $data): void
    {
        ['bitsHigh' => $bitsHigh, 'bitsEven' => $bitsEven] = $data;
        $this->assertNotSame($bitsHigh, $bitsEven);
        $this->assertNotSame(
            FilterableBit::HIGH->filter($type, ...$bits),
            FilterableExponent::EVEN->filter($type, ...$bits)
        );
    }

    public static function dataproviderBitsFilterValue(): array
    {
        $filterType = Type::ARRAY_FILTER_USE_VALUE;

        return [
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1
                ],
                'data' => [
                    'bitsLow'  => [
                    ],
                    'bitsEven' => [
                    ],
                    'bitsHigh' => [
                        0 => 1
                    ],
                    'bitsOdd'  => [
                        0 => 1
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 0,
                    1 => 1
                ],
                'data' => [
                    'bitsLow'  => [
                        0 => 0
                    ],
                    'bitsEven' => [
                        0 => 0
                    ],
                    'bitsHigh' => [
                        1 => 1
                    ],
                    'bitsOdd'  => [
                        1 => 1
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 1
                ],
                'data' => [
                    'bitsLow'  => [
                    ],
                    'bitsEven' => [
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        1 => 1
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        1 => 1
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 0,
                    1 => 0,
                    2 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        0 => 0,
                        1 => 0,
                    ],
                    'bitsEven' => [
                        0 => 0,
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        2 => 1,
                    ],
                    'bitsOdd'  => [
                        2 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        1 => 0,
                    ],
                    'bitsEven' => [
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        2 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                    ],
                    'bitsEven' => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        4 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 0,
                    4 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        1 => 0,
                        3 => 0,
                    ],
                    'bitsEven' => [
                        1 => 0,
                        3 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 1,
                    4 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        1 => 0,
                    ],
                    'bitsEven' => [
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 1,
                    4 => 1,
                    5 => 0,
                    6 => 0,
                    7 => 1,
                ],
                'data' => [
                    'bitsLow'  => [
                        1 => 0,
                        5 => 0,
                        6 => 0,
                    ],
                    'bitsEven' => [
                        1 => 0,
                        5 => 0,
                        6 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                        7 => 1,
                    ],
                    'bitsOdd'  => [
                        0 => 1,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                        7 => 1,
                    ],
                ],
            ],
        ];
    }

    public static function dataproviderBitsFilterKey(): array
    {
        $filterType = Type::ARRAY_FILTER_USE_KEY;

        return [
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1
                    ],
                    'bitsOdd'  => [
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 0,
                    1 => 1
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 0
                    ],
                    'bitsOdd'  => [
                        1 => 1
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 1
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 1
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 0,
                    1 => 0,
                    2 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 0,
                        2 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                        2 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                        2 => 0,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                        3 => 0,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 0,
                    4 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                        3 => 0,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 1,
                    4 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                        3 => 1,
                    ],
                ],
            ],
            [
                'type' => $filterType,
                'bits' => [
                    0 => 1,
                    1 => 0,
                    2 => 1,
                    3 => 1,
                    4 => 1,
                    5 => 0,
                    6 => 0,
                    7 => 1,
                ],
                'data' => [
                    'bitsEven' => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                        6 => 0,
                    ],
                    'bitsOdd'  => [
                        1 => 0,
                        3 => 1,
                        5 => 0,
                        7 => 1,
                    ],
                ],
            ],
        ];
    }
}