<?php
/**
 * Bit
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option\filter;

enum Bit: int implements Filterable
{
    case LOW = 0;
    case HIGH = 1;

    /**
     * Makes it callable so it can act as a filter for bits, depending on their status HIGH or LOW.
     *
     * @param int $bit
     *
     * @return bool
     */
    public function __invoke(int $bit): bool
    {
        return $this->value === $bit;
    }

    /**
     * Filters values based on HIGH or LOW.
     *
     * @param Type $type whether keys or values should be used.
     * @param int  ...$values
     *
     * @return array
     */
    public function filter(Type $type, int ...$values): array
    {
        return array_filter($values, $this, $type->mode());
    }
}
