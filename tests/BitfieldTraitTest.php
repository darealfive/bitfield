<?php
/**
 * BitfieldTraitTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'DataproviderTrait.php';

use Darealfive\Bitfield\Bitfield;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class BitfieldTraitTest covers trait methods of {@link \Darealfive\Bitfield\BitfieldTrait}
 */
final class BitfieldTraitTest extends TestCase
{
    use DataproviderTrait;

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

    #[DataProvider('dataproviderBitfieldHasFlags')]
    public function testBitfieldHasFlags(int $bitfield, BackedEnum ...$flags): void
    {
        $this->assertTrue(
            (new Bitfield($bitfield))->hasFlags(...$flags),
        );
    }

    #[DataProvider('dataproviderBitfieldHasNotFlags')]
    public function testBitfieldHasNotFlags(int $bitfield, BackedEnum ...$flags): void
    {
        $this->assertFalse(
            (new Bitfield($bitfield))->hasFlags(...$flags),
        );
    }

    #[DataProvider('dataproviderIntegers')]
    public function testGetBitfield(int $integer): void
    {
        $this->assertSame($integer, (new Bitfield($integer))->getBitfield());
    }

    #[DataProvider('dataproviderIntegers')]
    public function testGetBinary(int $integer): void
    {
        $this->assertSame(decbin($integer), (new Bitfield($integer))->getBinary());
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

    public static function dataproviderBitfieldHasFlags(): array
    {
        return [
            [
                'bitfield' => 1 + 2 + 4 + 8 + 16,
                Bit::D_1, Bit::D_2, Bit::D_4, Bit::D_8, Bit::D_16
            ],
            [
                'bitfield' => 1 + 2 + 4 + 8 + 16,
                Bit::D_4, Bit::D_8
            ],
            [
                'bitfield' => 1 + 2 + 4 + 8 + 16,
                Bit::D_8
            ],
        ];
    }

    public static function dataproviderBitfieldHasNotFlags(): array
    {
        return [
            [
                'bitfield' => 16,
                Bit::D_1, Bit::D_2, Bit::D_4, Bit::D_8
            ],
            [
                'bitfield' => 1,
                Bit::D_2, Bit::D_4, Bit::D_8, Bit::D_16
            ],
        ];
    }
}