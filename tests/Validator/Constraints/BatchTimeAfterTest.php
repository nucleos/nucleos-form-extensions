<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Validator\Constraints;

use Nucleos\Form\Validator\Constraints\BatchTimeAfter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class BatchTimeAfterTest extends TestCase
{
    public function testItIsNotInstantiableWithMissingFirstField(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessageMatches(sprintf('#^%s#', preg_quote('The options "firstField" must be set for constraint', '#')));

        new BatchTimeAfter([
            'secondField' => 'first',
        ]);
    }

    public function testItIsNotInstantiableWithMissingSecondField(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessageMatches(sprintf('#^%s#', preg_quote('The options "secondField" must be set for constraint', '#')));

        new BatchTimeAfter([
            'firstField' => 'first',
        ]);
    }

    public function testItIsNotInstantiableWithSameField(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The options "firstField" and "secondField" can not be the same for constraint '.BatchTimeAfter::class);

        new BatchTimeAfter([
            'firstField'  => 'first',
            'secondField' => 'first',
        ]);
    }

    public function testItIsInstantiable(): void
    {
        $dateAfter = new BatchTimeAfter([
            'firstField'  => 'first',
            'secondField' => 'second',
        ]);

        static::assertSame('first', $dateAfter->firstField);
        static::assertSame('second', $dateAfter->secondField);
    }

    public function testGetTarget(): void
    {
        $dateAfter = new BatchTimeAfter([
            'firstField'  => 'first',
            'secondField' => 'second',
        ]);

        static::assertSame('class', $dateAfter->getTargets());
    }
}
