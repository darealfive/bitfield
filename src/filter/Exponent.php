<?php
/**
 * Exponent
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield\filter;

/**
 * Exponent
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 *
 * The Exponent enum provides a way to filter integers based on whether they are even or odd.
 * It implements the Filterable interface, allowing it to be used as a callable for filtering operations.
 * This enum is particularly useful in scenarios where bitfield operations or filtering of numerical data
 * based on parity is required.
 *
 * @see    Filterable
 */
enum Exponent implements Filterable
{
    case EVEN;
    case ODD;

    /**
     * Makes it callable so it can act as a filter for exponents, depending on whether they are EVEN or ODD.
     *
     * @param int $exponent
     *
     * @return bool
     */
    public function __invoke(int $exponent): bool
    {
        $isEven = $exponent % 2 === 0;

        return match ($this) {
            self::EVEN => $isEven,
            self::ODD => !$isEven
        };
    }

    /**
     * Filters values based on EVEN or ODD.
     *
     * @param Type $type whether keys or values should be used.
     * @param int  ...$values
     *
     * @return array
     */
    public function filter(Type $type, int ...$values): array
    {
        return array_filter($values, $this, $type->mode());
    }
}
