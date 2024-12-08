<?php
/**
 * Filterable
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitmask\filter;

/**
 * Interface Filterable
 *
 * @package Darealfive\Bitmask\filter
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