<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Model;

use DateTimeInterface;

class BatchTime
{
    private int $day = 0;

    private ?DateTimeInterface $time = null;

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return 'Day: '.$this->day.', Time: '.(null !== $this->time ? $this->time->format('H:i:s') : 'null');
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(?int $day): void
    {
        $this->day = $day ?: 0;
    }

    public function getTime(): ?DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?DateTimeInterface $time): void
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
