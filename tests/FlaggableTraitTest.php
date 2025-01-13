<?php
/**
 * FlaggableTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'DataproviderTrait.php';

use Darealfive\Bitfield\Bitfield;
use Darealfive\Bitfield\Flaggable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

/**
 * Class FlaggableTest covers interface methods of {@link Flaggable}
 */
final class FlaggableTraitTest extends TestCase
{
    use DataproviderTrait;

    #[DataProvider('dataproviderBitfieldHasFlags')]
    public function testBitfieldHasFlags(int $bitfield, BackedEnum ...$flags): void
    {
        $this->assertTrue((new Bitfield($bitfield))->hasFlags(...$flags));
    }

    #[DataProvider('dataproviderBitfieldHasNotFlags')]
    public function testBitfieldHasNotFlags(int $bitfield, BackedEnum ...$flags): void
    {
        $this->assertFalse((new Bitfield($bitfield))->hasFlags(...$flags));
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

    /**
     * Tests that "setFlag" overwrites existing flags with the desired flag
     */
    #[Depends('testHasFlag')]
    #[DataProvider('dataproviderIntegers')]
    public function testSetFlag(int $integer): void
    {
        $flaggableOriginal = self::instantiateFlaggable($integer);

        $integerBits        = decbin($integer);
        $integerBitsMap     = array_map(intval(...), array_reverse(str_split($integerBits)));
        $integerHighBitsMap = array_filter($integerBitsMap, static fn(int $bit): bool => $bit === 1);
        $highBitsExponents  = array_keys($integerHighBitsMap);
        for ($i = 0; $i < count($highBitsExponents); $i++) {

            $highBitExponent = $highBitsExponents[$i];
            $highFlag        = 2 ** $highBitExponent;
            /*
             * Flaggable must have any bit of $integer also set to high
             */
            $this->assertTrue($flaggableOriginal->hasFlag($highFlag));

            $flaggable = self::instantiateFlaggable(0);
            $flaggable->setFlag($highFlag);
            foreach ($integerBitsMap as $exponent => $bit) {

                $flag = 2 ** $exponent;
                if ($exponent === $highBitExponent) {

                    $this->assertTrue($flaggable->hasFlag($flag));
                } else {

                    $this->assertFalse($flaggable->hasFlag($flag));
                }
            }
        }
    }

    #[DataProvider('dataproviderIntegers')]
    public function testHasFlag(int $integer): void
    {
        $flaggable      = self::instantiateFlaggable($integer);
        $integerBits    = decbin($integer);
        $integerBitsMap = array_map(intval(...), array_reverse(str_split($integerBits)));

        /*
         * Checks whether "hasFlag" detects every HIGH bit being set
         */
        foreach ($integerBitsMap as $exponent => $bit) {

            $flag = 2 ** $exponent;
            if ($bit === 1) {

                $this->assertTrue($flaggable->hasFlag($flag));
            } else {

                $this->assertFalse($flaggable->hasFlag($flag));
            }
        }
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

    private static function instantiateFlaggable(int $bitfield): Flaggable
    {
        return new Bitfield($bitfield);
    }
}