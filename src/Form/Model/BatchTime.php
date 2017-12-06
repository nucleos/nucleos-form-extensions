<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Model;

class BatchTime
{
    /**
     * @var int
     */
    private $day = 0;

    /**
     * @var \DateTime
     * */
    private $time;

    public function __toString()
    {
        return 'Day: '.$this->day.', Time: '.($this->time ? $this->time->format('H:i:s') : '');
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    /**
     * @return \DateTime|null
     */
    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->getDay() * 86400 + ($this->getTime() ? (int) $this->getTime()->format('U') : 0);
    }
}
