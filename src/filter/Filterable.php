<?php
/**
 * Filterable
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield\filter;

/**
 * Interface Filterable defines a contract for classes that can filter values based on their keys or values.
 * This interface is essential for implementing custom filters in applications where data needs to be selectively processed.
 *
 * @package Darealfive\Bitfield\filter
 */
interface Filterable
{
    /**
     * Filters values based on their keys or values.
     *
     * This method takes a Type enum to specify whether the filtering should be applied to keys or values.
     * It then accepts a variable number of integer values to be filtered.
     *
     * @param Type $type      specifies whether to filter keys or values
     * @param int  ...$values the integer values to be filtered
     *
     * @return array the filtered array
     *
     * @see Type
     */
    public function filter(Type $type, int ...$values): array;
}