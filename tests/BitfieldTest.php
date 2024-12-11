<?php
/**
 * BitfieldTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

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
}