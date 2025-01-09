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
     * New object from bitfield allows setting multiple bits at once.
     *
     * @param int $bitfield the bitfield to be set
     */
    public function __construct(int $bitfield = 0)
    {
        $this->setBitfield($bitfield);
    }

    /**
     * Instantiates a bitfield object from various typed arguments.
     * The bitfield gets build by BOOLEAN OR adding those bits together.
     *
     * @param int|float|bool|string|Stringable|BackedEnum $bit
     * @param int|float|bool|string|Stringable|BackedEnum ...$bits
     *
     * @return self
     */
    public static function fromBits(int|float|bool|string|Stringable|BackedEnum $bit,
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
}