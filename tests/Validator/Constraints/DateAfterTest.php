<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Validator\Constraints;

use Nucleos\Form\Validator\Constraints\DateAfter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class DateAfterTest extends TestCase
{
    public function testItIsNotInstantiableWithMissingFirstField(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessageMatches(sprintf('#^%s#', preg_quote('The options "firstField" must be set for constraint', '#')));

        new DateAfter([
            'secondField' => 'first',
        ]);
    }

    public function testItIsNotInstantiableWithMissingSecondField(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessageMatches(sprintf('#^%s#', preg_quote('The options "secondField" must be set for constraint', '#')));

        new DateAfter([
            'firstField' => 'first',
        ]);
    }

    public function testItIsNotInstantiableWithSameField(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The options "firstField" and "secondField" can not be the same for constraint '.DateAfter::class);

        new DateAfter([
            'firstField'  => 'first',
            'secondField' => 'first',
        ]);
    }

    public function testItIsInstantiable(): void
    {
        $dateAfter = new DateAfter([
            'firstField'  => 'first',
            'secondField' => 'second',
        ]);

        self::assertSame('first', $dateAfter->firstField);
        self::assertSame('second', $dateAfter->secondField);
    }

    public function testGetTarget(): void
    {
        $dateAfter = new DateAfter([
            'firstField'  => 'first',
            'secondField' => 'second',
        ]);

        self::assertSame('class', $dateAfter->getTargets());
    }
}
