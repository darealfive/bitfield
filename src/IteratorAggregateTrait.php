<?php
/**
 * IteratorAggregateTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitmask;

use Darealfive\Bitmask\filter\Filterable;
use Darealfive\Bitmask\filter\Type;

trait IteratorAggregateTrait
{
    use FlaggableTrait;

    /**
     * Implements {@link \IteratorAggregate} interface and optionally iterates only over values matching given filter.
     *
     * @return iterable<int,int>
     * @see FlaggableTrait::getFlags()
     */
    public function getIterator(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): array
    {
        return $this->getFlags($type, $filterable);
    }
}