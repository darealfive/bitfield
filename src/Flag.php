<?php
/**
 * Flag class file
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option;

use BackedEnum;
use Countable;
use IntBackedEnum;
use IteratorAggregate;
use Stringable;

/**
 * Basic implementation of handling flags.
 *
 * @see     FlaggableTrait
 * @see     Flaggable
 *
 * @package Darealfive\TruthTable\option
 */
final class Flag implements Flaggable, Countable, IteratorAggregate
{
    use CountableTrait, IteratorAggregateTrait;

    /**
     * Ensures that {@link FlaggableTrait::$_bits} gets initialized within constructor.
     *
     * @param int|IntBackedEnum $bit     a bit to be set
     * @param int|IntBackedEnum ...$bits additional bits to be set as well
     */
    public function __construct(int|IntBackedEnum $bit = 0, int|IntBackedEnum ...$bits)
    {
        $this->setFlag($bit, ...$bits);
    }

    /**
     * Instantiates flag.
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
            ...array_map(
                intval(...),
                array_map(
                    static fn(int|float|bool|string|Stringable|BackedEnum $bit) => (string) ($bit instanceof BackedEnum)
                        ? $bit->value
                        : $bit,
                    $bits
                )
            )
        );
    }
}