<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Fixtures;

class ExampleClass
{
    private mixed $end = null;

    private mixed $begin = null;

    public function getEnd(): mixed
    {
        return $this->end;
    }

    public function setEnd(mixed $end): void
    {
        $this->end = $end;
    }

    public function getBegin(): mixed
    {
        return $this->begin;
    }

    public function setBegin(mixed $begin): void
    {
        $this->begin = $begin;
    }
}
