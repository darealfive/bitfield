<?php
/**
 * CountableTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitmask;

use Darealfive\Bitmask\filter\Filterable;
use Darealfive\Bitmask\filter\Type;

trait CountableTrait
{
    use FlaggableTrait;

    /**
     * Implements {@link \Countable} interface and optionally counts only values matching given filter.
     *
     * @see FlaggableTrait::getFlags()
     */
    public function count(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): int
    {
        return count($this->getFlags($type, $filterable));
    }
}