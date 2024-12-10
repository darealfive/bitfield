<?php
/**
 * Filterable
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield\filter;

/**
 * Interface Filterable
 *
 * @package Darealfive\Bitfield\filter
 */
interface Filterable
{
    /**
     * Filters values bases on their keys or values.
     *
     * @param Type $type
     * @param int  ...$values
     *
     * @return array
     */
    public function filter(Type $type, int ...$values): array;
}