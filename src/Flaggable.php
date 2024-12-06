<?php
/**
 * Flaggable
 *
 * @author Sebastian Krein <sebastian@itstrategen.de>
 */

declare(strict_types=1);

namespace Darealfive\TruthTable\option;

use IntBackedEnum;

/**
 * Interface Flaggable defines a minimal set of methods to handle flags in a logical way.
 *
 * @package Darealfive\TruthTable\option
 */
interface Flaggable
{
    /**
     * Sets bits.
     *
     * @param int|IntBackedEnum $bit     a bit to be set
     * @param int|IntBackedEnum ...$bits additional bits to be set as well
     *
     * @return static
     */
    public function setFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static;

    /**
     * Adds bits.
     *
     * @param int|IntBackedEnum $bit     a bit to be added
     * @param int|IntBackedEnum ...$bits additional bits to be added as well
     *
     * @return static
     */
    public function addFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static;

    /**
     * Deletes bits.
     *
     * @param int|IntBackedEnum $bit     a bit to be deleted
     * @param int|IntBackedEnum ...$bits additional bits to be deleted as well
     *
     * @return static
     */
    public function delFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): static;

    /**
     * Checks whether at least one of the provided bits is set.
     *
     * @param int|IntBackedEnum $bit     a bit to be checked
     * @param int|IntBackedEnum ...$bits additional bits to be checked
     *
     * @return bool <TRUE> if at least one of the provided bits is set, <FALSE> otherwise.
     */
    public function hasFlag(int|IntBackedEnum $bit, int|IntBackedEnum ...$bits): bool;
}