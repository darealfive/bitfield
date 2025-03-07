<?php
/**
 * BitfieldTraitTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

use Darealfive\Bitfield\Bitfield;
use Darealfive\Bitfield\BitfieldTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class BitfieldTraitTest covers trait methods of {@link \Darealfive\Bitfield\BitfieldTrait}
 */
#[CoversClass(BitfieldTrait::class)]
final class BitfieldTraitTest extends TestCase
{
    #[DataProvider('dataproviderValidBits')]
    #[DataProvider('dataproviderInvalidBits')]
    public function testValidateBit(int $value, bool|int|string $result, bool $throw): void
    {
        if (is_string($result) && $throw) {

            $this->expectException($result);
        }

        $this->assertSame($result, Bitfield::validateBit($value, $throw));
    }

    #[DataProvider('dataproviderValidBitfields')]
    #[DataProvider('dataproviderInvalidBitfields')]
    public function testValidateBitfield(int $value, bool|int|string $result, bool $throw): void
    {
        if (is_string($result) && $throw) {

            $this->expectException($result);
        }

        $this->assertSame($result, Bitfield::validateBitfield($value, $throw));
    }

    #[DataProvider('dataproviderSumBits')]
    public function testSumBits(int $result, int ...$bits): void
    {
        $this->assertSame($result, Bitfield::sumBits(...$bits));
    }

    public static function dataproviderValidBitfields(): array
    {
        return [
            ['value' => -0, 'result' => 0, 'throw' => false],
            ['value' => -0, 'result' => 0, 'throw' => true],
            ['value' => 0, 'result' => 0, 'throw' => false],
            ['value' => 0, 'result' => 0, 'throw' => true],
            ['value' => 1, 'result' => 1, 'throw' => false],
            ['value' => 1, 'result' => 1, 'throw' => true],
            ['value' => 9999, 'result' => 9999, 'throw' => false],
            ['value' => 9999, 'result' => 9999, 'throw' => true],
        ];
    }

    public static function dataproviderInvalidBitfields(): array
    {
        $class = DomainException::class;

        return [
            ['value' => -1, 'result' => false, 'throw' => false],
            ['value' => -1, 'result' => $class, 'throw' => true],
            ['value' => -9999, 'result' => false, 'throw' => false],
            ['value' => -9999, 'result' => $class, 'throw' => true],
        ];
    }

    public static function dataproviderValidBits(): array
    {
        return [
            ['value' => -0, 'result' => 0, 'throw' => false],
            ['value' => -0, 'result' => 0, 'throw' => true],
            ['value' => 0, 'result' => 0, 'throw' => false],
            ['value' => 0, 'result' => 0, 'throw' => true],
            ['value' => 1, 'result' => 1, 'throw' => false],
            ['value' => 1, 'result' => 1, 'throw' => true],
            ['value' => 4, 'result' => 4, 'throw' => false],
            ['value' => 4, 'result' => 4, 'throw' => true],
            ['value' => 4096, 'result' => 4096, 'throw' => false],
            ['value' => 4096, 'result' => 4096, 'throw' => true],
        ];
    }

    public static function dataproviderInvalidBits(): array
    {
        $class = DomainException::class;

        return [
            ['value' => 3, 'result' => false, 'throw' => false],
            ['value' => 3, 'result' => $class, 'throw' => true],
            ['value' => 5, 'result' => false, 'throw' => false],
            ['value' => 5, 'result' => $class, 'throw' => true],
            ['value' => 4097, 'result' => false, 'throw' => false],
            ['value' => 4097, 'result' => $class, 'throw' => true],
            ['value' => -4, 'result' => false, 'throw' => false],
            ['value' => -4, 'result' => $class, 'throw' => true],
            ['value' => -5, 'result' => false, 'throw' => false],
            ['value' => -5, 'result' => $class, 'throw' => true],
            ['value' => -4096, 'result' => false, 'throw' => false],
            ['value' => -4096, 'result' => $class, 'throw' => true],
            ['value' => -4097, 'result' => false, 'throw' => false],
            ['value' => -4097, 'result' => $class, 'throw' => true],
        ];
    }

    public static function dataproviderSumBits(): array
    {
        return [
            [
                'result' => 0,
                0
            ],
            [
                'result' => 1,
                1
            ],
            [
                'result' => 1,
                1, 1
            ],
            [
                'result' => 3,
                1, 2
            ],
            [
                'result' => 3,
                1, 2, 2
            ],
            [
                'result' => 5,
                1, 4, 4, 1
            ],
            [
                'result' => 14,
                2, 8, 8, 4
            ],
            [
                'result' => 128,
                128
            ],
            [
                'result' => 1168,
                16, 1024, 128, 1024
            ],
        ];
    }
}