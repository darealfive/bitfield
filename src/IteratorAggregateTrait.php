<?php
/**
 * IteratorAggregateTrait
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use Darealfive\Bitfield\filter\Filterable;
use Darealfive\Bitfield\filter\Type;

trait IteratorAggregateTrait
{
    use BitfieldTrait;

    /**
     * Implements {@link \IteratorAggregate} interface and optionally iterates only over values matching given filter.
     *
     * @return iterable<int,int>
     * @see BitfieldTrait::getBits()
     */
    public function getIterator(Type $type = Type::ARRAY_FILTER_USE_VALUE, ?Filterable $filterable = null): array
    {
        return $this->getBits($type, $filterable);
    }
}