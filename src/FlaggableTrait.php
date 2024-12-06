<?php
/**
 * FlaggableTrait
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option;

use Darealfive\TruthTable\option\filter\Filterable;
use Darealfive\TruthTable\option\filter\Type;
use DomainException;
use IntBackedEnum;

/**
 * Trait FlaggableTrait implements the {@link Flaggable} interface.
 * It manages to store multiple "flags", each represented by a single BIT of an integer value.
 * It offers methods for setting, adding and removing bits via simple bitwise operations.
 * Since every flag is represented by a single BIT, this class only supports n² values, like: 0,1,2,4,8,16,32,64,128...
 *
 * @see https://www.php.net/manual/en/language.operators.bitwise.php
 */
trait FlaggableTrait
{
    /**
     * @var int the flags, each status represented by a single bit being "high" or "low".
     */
    private int $_bits;

    /**
     * Gets the current bitmask as decimal number.
     *
     * @return int e.g. 2
     */
    public function getFlag(): int
    {
        return $this->_bits;
    }

    /**
     * Gets the current bitmask.
     *
     * @return string e.g. 2 => '10'
     */
    public function getBinary(): string
    {
        return decbin($this->getFlag());
    }

    /**
     * Gets the current bits.
     *
     * @param Filterable|null $filterable optional filter to be applied on the returned bits
     *
     * @return iterable<int,int> maps exponent to the value of the bit
     */
    public function getFlags(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): array
    {
        $flags = array_map(
            intval(...),
            str_split($this->getBinary())
        );

        return $filterable?->filter($type, ...$flags) ?: $flags;
    }

    public function setFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        $this->_bits = self::sumFlags($bit, ...$bits);

        return $this;
    }

    public function addFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        return $this->setFlag($this->getFlag() | self::sumFlags($bit, ...$bits));
    }

    public function delFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static
    {
        return $this->setFlag($this->getFlag() & ~self::sumFlags($bit, ...$bits));
    }

    public function hasFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): bool
    {
        return ($this->getFlag() & self::sumFlags($bit, ...$bits)) !== 0;
    }

    /**
     * Checks whether all particular bits are set.
     *
     * @param int|IntBackedEnum $bit     a bit to be checked
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
    private static function sumFlags(int|IntBackedEnum $bit, int|IntBackedEnum  ...$bits): int
    {
        return array_sum(array_map(
            self::sanitizeFlag(...),
            array_merge($bits, [$bit])
        ));
    }

    /**
     * Converts IntBackedEnum to <int> and ensures that it represents an exact value of n² (any power of 2).
     *
     * @param int|IntBackedEnum $bit
     *
     * @return int the sanitized representation of given argument
     * @throws DomainException if the given value is not n² (a power of 2)
     */
    private static function sanitizeFlag(int|IntBackedEnum $bit): int
    {
        $int = is_int($bit) ? $bit : $bit->value;
        if ($int & ($int - 1) === 0) {

            return $int;
        }

        throw new DomainException("Integer value '$int' is not a power of 2!");
    }
}