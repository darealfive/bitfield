<?php
/**
 * CountableTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use Darealfive\Bitfield\filter\Filterable;
use Darealfive\Bitfield\filter\Type;

trait CountableTrait
{
    use BitfieldTrait;

    /**
     * Implements {@link \Countable} interface and optionally counts only values matching given filter.
     *
     * @see BitfieldTrait::getBits()
     */
    public function count(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): int
    {
        return count($this->getBits($type, $filterable));
    }
}