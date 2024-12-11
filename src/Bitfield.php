<?php
/**
 * Bitfield class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use BackedEnum;
use Countable;
use IntBackedEnum;
use IteratorAggregate;
use Stringable;

/**
 * Basic implementation of handling flags.
 *
 * @see     BitfieldTrait
 * @see     Flaggable
 *
 * @package Darealfive\Bitfield
 */
final class Bitfield implements Flaggable, Countable, IteratorAggregate
{
    use CountableTrait, IteratorAggregateTrait;

    /**
     * Ensures that {@link BitfieldTrait::$_bits} gets initialized within constructor.
     *
     * @param int|IntBackedEnum $bitfield the bitfield to be set
     */
    public function __construct(int|IntBackedEnum $bitfield = 0)
    {
        $this->setBitfield($bitfield);
    }

    /**
     * Instantiates a bitfield object from different typed arguments.
     * All values are converted to <string> (to support {@link Stringable}|{@link BackedEnum} types) and then back to
     * <int> as preparation for the constructor.
     *
     * @param int|float|bool|string|Stringable|BackedEnum $bit
     * @param int|float|bool|string|Stringable|BackedEnum ...$bits
     *
     * @return self
     */
    public static function from(int|float|bool|string|Stringable|BackedEnum $bit,
                                int|float|bool|string|Stringable|BackedEnum ...$bits): self
    {
        $bits[] = $bit;
        return new self(
            self::sumBits(
                ...array_map(
                    self::sanitize(...),
                    $bits
                )
            )
        );
    }

    public static function sanitize(int|float|bool|string|Stringable|BackedEnum $bit): int
    {
        return (int)(string)(($bit instanceof BackedEnum) ? $bit->value : $bit);
    }
}