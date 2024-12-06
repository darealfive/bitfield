<?php
/**
 * Type
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option\filter;

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
