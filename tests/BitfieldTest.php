<?php
/**
 * BitfieldTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Bit.php';

use Darealfive\Bitfield\Bitfield;
use Darealfive\Bitfield\BitfieldTrait;
use Darealfive\Bitfield\FlaggableTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * Class BitfieldTest
 */
#[UsesClass(FlaggableTrait::class)]
#[UsesClass(BitfieldTrait::class)]
#[CoversClass(Bitfield::class)]
final class BitfieldTest extends TestCase
{
    #[DataProvider('dataproviderValidConstructorArguments')]
    #[DataProvider('dataproviderInvalidConstructorArguments')]
    public function testConstructor(mixed $value, int|string $result): void
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
        return [
            ['value' => -1, 'result' => ($class = DomainException::class)],
            ['value' => -9999, 'result' => $class],
            ['value' => -PHP_INT_MAX, 'result' => $class],
            ['value' => Bit::D_16, 'result' => TypeError::class],
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