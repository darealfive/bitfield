<?php
/**
 * BitfieldTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use Darealfive\Bitfield\filter\Filterable;
use Darealfive\Bitfield\filter\Type;
use DomainException;
use IntBackedEnum;

/**
 * Trait BitfieldTrait implements the {@link Flaggable} interface.
 * It manages to store multiple "flags", each represented by a single BIT of an integer value.
 * It offers methods for setting, adding and removing bits via simple bitwise operations.
 * Since every flag is represented by a single BIT, this class only supports n² values, like: 0,1,2,4,8,16,32,64,128...
 *
 * @see https://www.php.net/manual/en/language.operators.bitwise.php
 */
trait BitfieldTrait
{
    /**
     * @var int the whole bitfield, represented by an unsigned <int>. Defaults to 0 meaning no bit is currently set.
     */
    private int $_bitfield = 0;

    /**
     * Gets the current bitfield as <string> representation.
     *
     * @return string e.g. 2 => '10'
     */
    public function getBinary(): string
    {
        return decbin($this->getBitfield());
    }

    /**
     * Gets the current bitfield.
     *
     * @return int e.g. 2
     */
    public function getBitfield(): int
    {
        return $this->_bitfield;
    }

    /**
     * Sets the bitfield.
     *
     * @param int|IntBackedEnum $bitfield
     *
     * @return static
     */
    public function setBitfield(int|IntBackedEnum $bitfield): static
    {
        $this->_bitfield = self::validateBitfield($bitfield, true);

        return $this;
    }

    /**
     * Gets the current bits as a list of <int> exponents mapped to either <int> 1 for "high" or <int> 0 for "low".
     *
     * @param Filterable|null $filterable optional filter to be applied on the returned bits
     *
     * @return iterable<int,int> maps exponent to the value of the bit
     */
    public function getBits(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): array
    {
        $bits = array_map(
            intval(...),
            str_split($this->getBinary())
        );

        return $filterable?->filter($type, ...$bits) ?: $bits;
    }

    public function setFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        return $this->setBitfield(self::sumBits($bit, ...$bits));
    }

    public function addFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        return $this->setFlag($this->getBitfield() | self::sumBits($bit, ...$bits));
    }

    public function delFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        return $this->setFlag($this->getBitfield() & ~self::sumBits($bit, ...$bits));
    }

    public function hasFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): bool
    {
        return ($this->getBitfield() & self::sumBits($bit, ...$bits)) !== 0;
    }

    /**
     * Checks whether all particular bits are set.
     *
     * @param int|IntBackedEnum $bit a bit to be checked
     * @param int|IntBackedEnum ...$bits additional bits to be checked
     *
     * @return bool <TRUE> if all provided bits are set, <FALSE> otherwise.
     */
    public function hasFlags(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): bool
    {
        $bits[] = $bit;

        return count($bits) === count(array_filter($bits, $this->hasFlag(...)));
    }

    /**
     * Sum of given bits.
     *
     * @param int|IntBackedEnum $bit
     * @param int|IntBackedEnum ...$bits
     *
     * @return int the decimal sum of all given bits.
     */
    final public static function sumBits(int|IntBackedEnum $bit, int|IntBackedEnum  ...$bits): int
    {
        return array_sum(array_map(
            self::normalizeBit(...),
            array_merge($bits, [$bit])
        ));
    }

    /**
     * Converts IntBackedEnum to <int> and ensures that it represents an exact value of n² (any power of 2).
     *
     * @param int|IntBackedEnum $int
     * @param bool $throw whether to throw an exception instead of returning <false>
     *
     * @return int|false the normalized representation of given argument
     */
    final public static function normalizeBit(int|IntBackedEnum $int, bool $throw = true): int|false
    {
        $int = is_int($int) ? $int : $int->value;
        return self::validateBit($int, $throw);
    }

    /**
     * Validates given value to be usable within a bitfield, which is the case if it is positive.
     *
     * @param int $int
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
     * @param int $int
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