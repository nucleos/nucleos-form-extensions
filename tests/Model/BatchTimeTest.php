<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Model;

use Core23\Form\Model\BatchTime;
use DateTime;
use PHPUnit\Framework\TestCase;

final class BatchTimeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        date_default_timezone_set('UTC');
    }

    public function testItIsInstantiable(): void
    {
        $batchTime = new BatchTime();

        static::assertSame('Day: 0, Time: null', $batchTime->__toString());
        static::assertSame('Day: 0, Time: null', $batchTime->toString());
    }

    public function testDay(): void
    {
        $batchTime = new BatchTime();
        $batchTime->setDay(3);

        static::assertSame(3, $batchTime->getDay());
    }

    public function testTime(): void
    {
        $time = new DateTime('1970-01-01 14:23');

        $batchTime = new BatchTime();
        $batchTime->setTime($time);

        static::assertSame($time, $batchTime->getTime());
    }

    public function testSeconds(): void
    {
        $time = new DateTime('1970-01-01 14:23');

        $batchTime = new BatchTime();
        $batchTime->setDay(2);
        $batchTime->setTime($time);

        static::assertSame(224580, $batchTime->getSeconds());
    }
}
