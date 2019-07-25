<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Model;

use DateTime;

class BatchTime
{
    /**
     * @var int
     */
    private $day = 0;

    /**
     * @var DateTime|null
     */
    private $time;

    public function __toString()
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return 'Day: '.$this->day.', Time: '.($this->time ? $this->time->format('H:i:s') : 'null');
    }

    public function getDay(): int
    {
        return $this->day ?: 0;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    public function setTime(DateTime $time): void
    {
        $this->time = $time;
    }

    public function getSeconds(): int
    {
        $seconds =  $this->getDay() * 86400;

        if (null !== $this->getTime()) {
            $time = clone $this->getTime();
            $seconds += (int) $time->format('U');
        }

        return $seconds;
    }
}
