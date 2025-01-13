<?php
/**
 * BitfieldTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use DomainException;
use BackedEnum;
use Stringable;

/**
 * Trait BitfieldTrait provides static functions to handle bit data
 */
trait BitfieldTrait
{
    /**
     * Sum of given bits. Because we treat arguments as real "bits", this method only sums unique values.
     * E.g.:
     * Input: 2,8,8,4
     * Output: 14 (2+8+4)
     *
     * @param int|BackedEnum $bit
     * @param int|BackedEnum ...$bits
     *
     * @return int the decimal sum of all given (unique) bits.
     */
    final public static function sumBits(int|BackedEnum $bit, int|BackedEnum  ...$bits): int
    {
        return array_sum(array_unique(array_map(
            self::normalizeBit(...),
            array_merge($bits, [$bit])
        )));
    }

    /**
     * Converts BackedEnum to <int> and ensures that it represents an exact value of n² (any power of 2).
     *
     * @param int|BackedEnum $int
     * @param bool           $throw whether to throw an exception instead of returning <false>
     *
     * @return int|false the normalized representation of given argument
     */
    final public static function normalizeBit(int|BackedEnum $int, bool $throw = true): int|false
    {
        return self::validateBit(self::sanitize($int), $throw);
    }

    /**
     * Converts different typed arguments to <int>
     *
     * @param int|float|bool|string|Stringable|BackedEnum $bit
     *
     * @return int the integer representation
     */
    final public static function sanitize(int|float|bool|string|Stringable|BackedEnum $bit): int
    {
        return (int) (string) (($bit instanceof BackedEnum) ? $bit->value : $bit);
    }

    /**
     * Validates given value to be usable within a bitfield, which is the case if it is positive.
     *
     * @param int  $int
     * @param bool $throw whether to throw an exception instead of returning <false>
     *
     * @return int|false given <int> if value is positive, <false> otherwise
     */
    final public static function validateBitfield(int $int, bool $throw = false): int|false
    {
        return $int >= 0
            ? $int
            : !(!$throw ?: throw new DomainException("Value '$int' is not positive!"));
    }

    /**
     * Validates given value by checking if it is of n² (any power of 2).
     *
     * @param int  $int
     * @param bool $throw whether to throw an exception instead of returning <false>
     *
     * @return int|false given <int> if value is n² (a power of 2), <false> otherwise
     */
    final public static function validateBit(int $int, bool $throw = false): int|false
    {
        return ($int & ($int - 1)) === 0
            ? $int
            : !(!$throw ?: throw new DomainException("Value '$int' is not a power of 2!"));
    }
}