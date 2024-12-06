<?php
/**
 * CountableTrait
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option;

use Darealfive\TruthTable\option\filter\Filterable;
use Darealfive\TruthTable\option\filter\Type;

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