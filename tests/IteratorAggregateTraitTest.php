<?php
/**
 * IteratorAggregateTraitTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

use Darealfive\Bitfield\Bitfield;
use Darealfive\Bitfield\filter\Bit as BitFilter;
use Darealfive\Bitfield\filter\Type;
use Darealfive\Bitfield\FlaggableTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

/**
 * Class IteratorAggregateTraitTest covers interface methods of {@link \Darealfive\Bitfield\IteratorAggregateTrait}
 */
#[CoversClass(FlaggableTrait::class)]
#[CoversClass(IteratorAggregateTraitTest::class)]
final class IteratorAggregateTraitTest extends TestCase
{
    public function testGetIteratorReturnsArray(): void
    {
        $this->assertIsArray(self::instantiateFlaggable(1)->getIterator());
    }

    #[DataProvider('dataproviderBitsFilter')]
    public function testGetBitsFiltersNoBits(int $dec, array $data): void
    {
        ['bin' => $bin, 'bits' => $bits] = $data;
        $bitfield = self::instantiateFlaggable($dec);
        $this->assertSame($dec, $bitfield->getBitfield());
        $this->assertSame($bin, $bitfield->getBinary());

        $filterable = null;
        $type       = Type::ARRAY_FILTER_USE_VALUE;
        $this->assertSame($bits, $bitfield->getBits());
        $this->assertSame($bits, $bitfield->getBits(filterable: $filterable));
        $this->assertSame($bits, $bitfield->getBits($type));
        $this->assertSame($bits, $bitfield->getBits($type, $filterable));
    }

    #[Depends('testGetBitsFiltersNoBits')]
    #[DataProvider('dataproviderBitsFilter')]
    public function testGetBitsFiltersLowBits(int $dec, array $data): void
    {
        ['bitsLow' => $bitsLow] = $data;
        $bitfield = self::instantiateFlaggable($dec);

        $type = Type::ARRAY_FILTER_USE_VALUE;
        $this->assertSame($bitsLow, $bitfield->getBits($type, BitFilter::LOW));
        $this->assertSame($bitsLow, $bitfield->getBits(filterable: BitFilter::LOW));
    }

    #[Depends('testGetBitsFiltersNoBits')]
    #[DataProvider('dataproviderBitsFilter')]
    public function testGetBitsFiltersHighBits(int $dec, array $data): void
    {
        ['bitsHigh' => $bitsHigh] = $data;
        $bitfield = self::instantiateFlaggable($dec);

        $type = Type::ARRAY_FILTER_USE_VALUE;
        $this->assertSame($bitsHigh, $bitfield->getBits($type, BitFilter::HIGH));
        $this->assertSame($bitsHigh, $bitfield->getBits(filterable: BitFilter::HIGH));
    }

    public static function dataproviderBitsFilter(): array
    {
        return [
            [
                'dec'  => 0,
                'data' => [
                    'bin'      => '0',
                    'bits'     => [],
                    'bitsLow'  => [],
                    'bitsHigh' => [],
                ],
            ],
            [
                'dec'  => 1,
                'data' => [
                    'bin'      => '1',
                    'bits'     => [
                        0 => 1
                    ],
                    'bitsLow'  => [
                    ],
                    'bitsHigh' => [
                        0 => 1
                    ],
                ],
            ],
            [
                'dec'  => 2,
                'data' => [
                    'bin'      => '10',
                    'bits'     => [
                        0 => 0,
                        1 => 1
                    ],
                    'bitsLow'  => [
                        0 => 0
                    ],
                    'bitsHigh' => [
                        1 => 1
                    ],
                ],
            ],
            [
                'dec'  => 3,
                'data' => [
                    'bin'      => '11',
                    'bits'     => [
                        0 => 1,
                        1 => 1
                    ],
                    'bitsLow'  => [
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        1 => 1
                    ],
                ],
            ],
            [
                'dec'  => 4,
                'data' => [
                    'bin'      => '100',
                    'bits'     => [
                        0 => 0,
                        1 => 0,
                        2 => 1,
                    ],
                    'bitsLow'  => [
                        0 => 0,
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        2 => 1,
                    ],
                ],
            ],
            [
                'dec'  => 5,
                'data' => [
                    'bin'      => '101',
                    'bits'     => [
                        0 => 1,
                        1 => 0,
                        2 => 1,
                    ],
                    'bitsLow'  => [
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                    ],
                ],
            ],
            [
                'dec'  => 17,
                'data' => [
                    'bin'      => '10001',
                    'bits'     => [
                        0 => 1,
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 1,
                    ],
                    'bitsLow'  => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        4 => 1,
                    ],
                ],
            ],
            [
                'dec'  => 21,
                'data' => [
                    'bin'      => '10101',
                    'bits'     => [
                        0 => 1,
                        1 => 0,
                        2 => 1,
                        3 => 0,
                        4 => 1,
                    ],
                    'bitsLow'  => [
                        1 => 0,
                        3 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                        4 => 1,
                    ],
                ],
            ],
            [
                'dec'  => 29,
                'data' => [
                    'bin'      => '11101',
                    'bits'     => [
                        0 => 1,
                        1 => 0,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                    ],
                    'bitsLow'  => [
                        1 => 0,
                    ],
                    'bitsHigh' => [
                        0 => 1,
                        2 => 1,
                        3 => 1,
                        4 => 1,
                    ],
                ],
            ],
        ];
    }

    private static function instantiateFlaggable(int $bitfield): Bitfield
    {
        return new Bitfield($bitfield);
    }
}