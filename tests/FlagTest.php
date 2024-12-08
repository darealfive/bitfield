<?php
/**
 * FlagTest class file
 *
 * @author Sebastian Krein <darealfive@gmx.de>
 */

use Darealfive\Bitmask\Flag;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagTest
 */
final class FlagTest extends TestCase
{
    public function testCanBeCreatedFromInt(): void
    {
        $integers = [1, 0, 0, 1, 0, 1, 1];
        $flag = new Flag(...$integers);


        $this->assertSame($integers, $flag->getFlags());
    }
}