<?php
/**
 * Filterable
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option\filter;

/**
 * Interface Filterable
 *
 * @package Darealfive\TruthTable\option\filter
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