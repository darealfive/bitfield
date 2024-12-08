<?php
/**
 * Type
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitmask\filter;

enum Type
{
    case ARRAY_FILTER_USE_VALUE;
    case ARRAY_FILTER_USE_KEY;

    public function mode(): int
    {
        return match ($this) {
            self::ARRAY_FILTER_USE_VALUE => 0,
            self::ARRAY_FILTER_USE_KEY => ARRAY_FILTER_USE_KEY,
        };
    }
}
