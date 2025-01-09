<?php
/**
 * BitfieldTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

use Darealfive\Bitfield\Bitfield;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class BitfieldTest
 */
final class BitfieldTest extends TestCase
{
    #[DataProvider('dataproviderValidConstructorArguments')]
    #[DataProvider('dataproviderInvalidConstructorArguments')]
    public function testConstructor(int $value, int|string $result): void
    {
        if (is_string($result)) {

            $this->expectException($result);
        }

        $this->assertInstanceOf(Bitfield::class, new Bitfield($value));
    }

    #[DataProvider('dataproviderValidConstructorArguments')]
    public function testGetBitfield(int $value, int $result): void
    {
        $this->assertSame($value, (new Bitfield($value))->getBitfield());
    }

    #[DataProvider('dataproviderEqualBits')]
    public function testFactoryFromBits(mixed ...$values): void
    {
        $count = count($values);
        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $this->assertSame(
                    Bitfield::fromBits($values[$i])->getBitfield(),
                    Bitfield::fromBits($values[$j])->getBitfield()
                );
            }
        }
    }

    #[DataProvider('dataproviderEqualBits')]
    public function testConstructorFromBitfields(mixed ...$values): void
    {
        $count = count($values);
        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $this->assertSame(
                    (new Bitfield(Bitfield::sanitize($values[$i])))->getBitfield(),
                    (new Bitfield(Bitfield::sanitize($values[$j])))->getBitfield()
                );
            }
        }
    }

    #[DataProvider('dataproviderUnequalBits')]
    public function testFactoryFromUnequal(array $a, array $b): void
    {
        //TODO what is the expectation to this factory?
        $this->assertNotSame(
            Bitfield::fromBits(...$a)->getBitfield(),
            Bitfield::fromBits(...$b)->getBitfield()
        );
    }

    #[DataProvider('dataproviderEqualBitfields')]
    public function testSanitizeReturnsInt(mixed ...$values): void
    {
        foreach ($values as $value) {

            $sanitized = Bitfield::sanitize($value);
            $this->assertIsInt($sanitized);

            if ($value instanceof Stringable) {

                $value = (string) $value;
            } elseif ($value instanceof BackedEnum) {
                $value = $value->value;
            }
            $this->assertEqualsWithDelta($value, $sanitized, 0.9);
        }
    }

    #[DataProvider('dataproviderBitfieldHasFlags')]
    public function testBitfieldHasFlags(int $bitfield, Bit ...$flags): void
    {
        $this->assertTrue(
            (new Bitfield($bitfield))->hasFlags(...$flags),
        );
    }

    #[DataProvider('dataproviderBitfieldHasNotFlags')]
    public function testBitfieldHasNotFlags(int $bitfield, Bit ...$flags): void
    {
        $this->assertFalse(
            (new Bitfield($bitfield))->hasFlags(...$flags),
        );
    }

    #[DataProvider('dataproviderBitfieldHasFlags')]
    public function testBitfieldHasFlag(int $bitfield, Bit...$flags): void
    {
        foreach ($flags as $flag) {
            $this->assertTrue(
                (new Bitfield($bitfield))->hasFlag($flag),
            );
        }
    }

    #[DataProvider('dataproviderBitfieldHasNotFlags')]
    public function testBitfieldHasNotFlag(int $bitfield, Bit...$flags): void
    {
        foreach ($flags as $flag) {
            $this->assertFalse(
                (new Bitfield($bitfield))->hasFlag($flag),
            );
        }
    }

    public static function dataproviderValidConstructorArguments(): array
    {
        return [
            ['value' => -0, 'result' => 0],
            ['value' => 0, 'result' => 0],
            ['value' => 1, 'result' => 1],
            ['value' => 2, 'result' => 2],
            ['value' => 3, 'result' => 3],
            ['value' => 4, 'result' => 4],
            ['value' => 5, 'result' => 5],
            ['value' => 9999, 'result' => 9999],
            ['value' => PHP_INT_MAX, 'result' => PHP_INT_MAX],
        ];
    }

    public static function dataproviderInvalidConstructorArguments(): array
    {
        $class = DomainException::class;

        return [
            ['value' => -1, 'result' => $class],
            ['value' => -9999, 'result' => $class],
            ['value' => -PHP_INT_MAX, 'result' => $class],
        ];
    }

    public static function dataproviderUnequalBits(): array
    {
        return [
            [
                ...self::dataproviderEqualBits()
            ],
        ];
    }

    public static function dataproviderUnequalBitfields(): array
    {
        return [
            [
                ...self::dataproviderEqualBitfields()
            ],
        ];
    }

    public static function dataproviderEqualBits(): array
    {
        return [
            [
                false, 0, '0', 0.0, '0.0', self::stringable(0), self::stringable('0'), self::stringable(0.0),
                self::stringable('0.0'), self::stringable(self::stringable(0))
            ],
            [
                true, 1, '1', 1.1, '1.1', Bit::D_1, self::stringable(1), self::stringable('1'), self::stringable(1.1),
                self::stringable('1.1'), self::stringable(self::stringable(1))
            ],
            [
                16, '16', 16.16, '16.16', Bit::D_16, self::stringable(16), self::stringable('16'),
                self::stringable(16.16), self::stringable('16.16'), self::stringable(self::stringable(16))
            ],
        ];
    }

    public static function dataproviderEqualBitfields(): array
    {
        return [
            [
                3, '3', 3.3, '3.3', self::stringable(3), self::stringable('3'), self::stringable(3.3),
                self::stringable('3.3'), self::stringable(self::stringable(3))
            ],
            [
                255, '255', 255.255, '255.255', self::stringable(255), self::stringable('255'),
                self::stringable(255.255), self::stringable('255.255'), self::stringable(self::stringable(255))
            ],
            // BIT's are also treated as BITFIELD's.
            // All single BIT's are BITFIELD, but not all BITFIELDS represents as a single BIT.
            ...self::dataproviderEqualBits()
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
                'decimal' => 1,
                Bit::D_2, Bit::D_4, Bit::D_8, Bit::D_16
            ],
        ];
    }

    public static function stringable(mixed $value): Stringable
    {
        return new class($value) implements Stringable {
            public function __construct(public int|float|string|Stringable $value)
            {
            }

            public function __toString(): string
            {
                return (string) $this->value;
            }
        };
    }
}

enum Bit: int
{
    case D_1 = 1 << 0;
    case D_2 = 1 << 1;
    case D_4 = 1 << 2;
    case D_8 = 1 << 3;
    case D_16 = 1 << 4;
}