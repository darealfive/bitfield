<?php
/**
 * BitfieldTraitTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

use Darealfive\Bitfield\Bitfield;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class BitfieldTraitTest
 */
final class BitfieldTraitTest extends TestCase
{
    #[DataProvider('dataproviderBit')]
    public function testIsBit(int $value, bool|int|string $result, bool $throw): void
    {
        if (is_string($result) && $throw) {

            $this->expectException($result);
        }

        $this->assertSame($result, Bitfield::validateBit($value, $throw));
    }

    #[DataProvider('dataproviderBitfield')]
    public function testIsBitfield(int $value, bool|int|string $result, bool $throw): void
    {
        if (is_string($result) && $throw) {

            $this->expectException($result);
        }

        $this->assertSame($result, Bitfield::validateBitfield($value, $throw));
    }

    public static function dataproviderBitfield(): array
    {
        return [
            ['value' => 0, 'result' => 0, 'throw' => false],
            ['value' => 1, 'result' => 1, 'throw' => false],
            ['value' => 9999, 'result' => 9999, 'throw' => false],
            ['value' => -1, 'result' => false, 'throw' => false],
            ['value' => -9999, 'result' => false, 'throw' => false],

            ['value' => 0, 'result' => 0, 'throw' => true],
            ['value' => 1, 'result' => 1, 'throw' => true],
            ['value' => 9999, 'result' => 9999, 'throw' => true],
            ['value' => -9999, 'result' => DomainException::class, 'throw' => true],
            ['value' => -1, 'result' => DomainException::class, 'throw' => true],
            ['value' => -9999, 'result' => DomainException::class, 'throw' => true],
        ];
    }

    public static function dataproviderBit(): array
    {
        return [
            ['value' => 0, 'result' => 0, 'throw' => false],
            ['value' => 1, 'result' => 1, 'throw' => false],
            ['value' => 3, 'result' => false, 'throw' => false],
            ['value' => 4, 'result' => 4, 'throw' => false],
            ['value' => 5, 'result' => false, 'throw' => false],
            ['value' => 4096, 'result' => 4096, 'throw' => false],
            ['value' => 4097, 'result' => false, 'throw' => false],
            ['value' => -4, 'result' => false, 'throw' => false],
            ['value' => -5, 'result' => false, 'throw' => false],
            ['value' => -4096, 'result' => false, 'throw' => false],
            ['value' => -4097, 'result' => false, 'throw' => false],

            ['value' => 0, 'result' => 0, 'throw' => true],
            ['value' => 1, 'result' => 1, 'throw' => true],
            ['value' => 3, 'result' => DomainException::class, 'throw' => true],
            ['value' => 4, 'result' => 4, 'throw' => true],
            ['value' => 5, 'result' => DomainException::class, 'throw' => true],
            ['value' => 5, 'result' => DomainException::class, 'throw' => true],
            ['value' => 4096, 'result' => 4096, 'throw' => true],
            ['value' => 4097, 'result' => DomainException::class, 'throw' => true],
            ['value' => -4, 'result' => DomainException::class, 'throw' => true],
            ['value' => -5, 'result' => DomainException::class, 'throw' => true],
            ['value' => -4096, 'result' => DomainException::class, 'throw' => true],
            ['value' => -4097, 'result' => DomainException::class, 'throw' => true],
        ];
    }
}