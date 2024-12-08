<?php
/**
 * Exponent
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitmask\filter;

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
        $isEven = $exponent % 2;

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
