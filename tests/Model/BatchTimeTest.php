<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Model;

use DateTime;
use Nucleos\Form\Model\BatchTime;
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

        self::assertSame('Day: 0, Time: null', $batchTime->__toString());
        self::assertSame('Day: 0, Time: null', $batchTime->toString());
    }

    public function testDay(): void
    {
        $batchTime = new BatchTime();
        $batchTime->setDay(3);

        self::assertSame(3, $batchTime->getDay());
    }

    public function testTime(): void
    {
        $time = new DateTime('1970-01-01 14:23');

        $batchTime = new BatchTime();
        $batchTime->setTime($time);

        self::assertSame($time, $batchTime->getTime());
    }

    public function testSeconds(): void
    {
        $time = new DateTime('1970-01-01 14:23');

        $batchTime = new BatchTime();
        $batchTime->setDay(2);
        $batchTime->setTime($time);

        self::assertSame(224580, $batchTime->getSeconds());
    }
}
