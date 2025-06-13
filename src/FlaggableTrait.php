<?php
/**
 * FlaggableTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use BackedEnum;
use Darealfive\Bitfield\filter\Filterable;
use Darealfive\Bitfield\filter\Type;

/**
 * Trait FlaggableTrait implements the {@link Flaggable} interface.
 *
 * You can add, update and delete multiple "flags".
 * Each flag is represented by a single BIT within an integer value.
 *
 * The logic distinguishes between two integer types, which are also called the same in the method arguments:
 * 1. "bit":int         =>  MUST BE of any n² value (any power of 2 like 0,1,2,4,8,16,32,64,128...)
 * 2. "bitfield":int    =>  any integer value
 * So everytime you see an argument called "$bit", pass some n² int value.
 *
 * @see https://www.php.net/manual/en/language.operators.bitwise.php
 */
trait FlaggableTrait
{
    use BitfieldTrait;

    /**
     * @var int the whole bitfield, represented by an unsigned <int>. Defaults to 0 meaning no bit is currently set.
     */
    private int $_bitfield = 0;

    public function setFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static
    {
        return $this->setBitfield(self::sumBits($bit, ...$bits));
    }

    public function addFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static
    {
        return $this->setBitfield($this->getBitfield() | self::sumBits($bit, ...$bits));
    }

    public function delFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static
    {
        return $this->setFlag($this->getBitfield() & ~self::sumBits($bit, ...$bits));
    }

    public function hasFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): bool
    {
        return ($this->getBitfield() & self::sumBits($bit, ...$bits)) !== 0;
    }

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
     * @param int $bitfield
     *
     * @return static
     */
    public function setBitfield(int $bitfield): static
    {
        $this->_bitfield = self::validateBitfield($bitfield, true);

        return $this;
    }

    /**
     * Gets the current bits as a list of <int> exponents mapped to either <int> 1 for "high" or <int> 0 for "low".
     * If the current bitfield equals 0, then an empty array gets returned.
     *
     * @param Filterable|null $filterable optional filter to be applied on the returned bits
     *
     * @return iterable<int,int> maps exponent to the value of the bit
     */
    public function getBits(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): array
    {
        $binary = $this->getBinary();
        if ($binary === '0') {
            return [];
        }

        $bits = array_map(
            intval(...),
            array_reverse(str_split($binary))
        );

        return $filterable?->filter($type, ...$bits) ?? $bits;
    }

    /**
     * Checks whether all particular bits are set.
     *
     * @param int|BackedEnum $bit     a bit to be checked
     * @param int|BackedEnum ...$bits additional bits to be checked
     *
     * @return bool <TRUE> if all provided bits are set, <FALSE> otherwise.
     */
    public function hasFlags(int|BackedEnum $bit, int|BackedEnum ...$bits): bool
    {
        $bits[] = $bit;

        return count($bits) === count(array_filter($bits, $this->hasFlag(...)));
    }
}