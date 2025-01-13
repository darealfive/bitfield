<?php
/**
 * Flaggable
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

declare(strict_types=1);

namespace Darealfive\Bitfield;

use BackedEnum;

/**
 * Interface Flaggable defines a minimal set of methods to handle flags in a logical way.
 *
 * @package Darealfive\Bitfield
 */
interface Flaggable
{
    /**
     * Sets bits.
     *
     * @param int|BackedEnum $bit     a bit to be set
     * @param int|BackedEnum ...$bits additional bits to be set as well
     *
     * @return static
     */
    public function setFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static;

    /**
     * Adds bits.
     *
     * @param int|BackedEnum $bit     a bit to be added
     * @param int|BackedEnum ...$bits additional bits to be added as well
     *
     * @return static
     */
    public function addFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static;

    /**
     * Deletes bits.
     *
     * @param int|BackedEnum $bit     a bit to be deleted
     * @param int|BackedEnum ...$bits additional bits to be deleted as well
     *
     * @return static
     */
    public function delFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): static;

    /**
     * Checks whether at least one of the provided bits is set.
     *
     * @param int|BackedEnum $bit     a bit to be checked
     * @param int|BackedEnum ...$bits additional bits to be checked
     *
     * @return bool <TRUE> if at least one of the provided bits is set, <FALSE> otherwise.
     */
    public function hasFlag(int|BackedEnum $bit, int|BackedEnum ...$bits): bool;
}