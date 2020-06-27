<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Validator\Constraints;

use DateTime;
use InvalidArgumentException;
use Nucleos\Form\Tests\Fixtures\DummyConstraint;
use Nucleos\Form\Validator\Constraints\DateAfter;
use Nucleos\Form\Validator\Constraints\DateAfterValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class DateAfterValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Nucleos\Form\Validator\Constraints\DateAfter", "Nucleos\Form\Tests\Fixtures\DummyConstraint" given');

        $this->validator->validate('dummy', new DummyConstraint());
    }

    public function testValidateInvalidObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not validate "string"');

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate('dummy', $constraint);
    }

    public function testValidateInvalidFirstField(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getEnd'])
            ->getMock()
        ;
        $object->method('getEnd')->willReturn(new DateTime());

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateInvalidSecondField(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime());

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateInvalidFirstValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime());
        $object->method('getEnd')->willReturn('test');

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateInvalidSecondValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn('test');
        $object->method('getEnd')->willReturn(new DateTime());

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateEmptyFirstValue(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(null);
        $object->method('getEnd')->willReturn(new DateTime());

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->firstField)
            ->setParameter('%field%', $constraint->secondField)
            ->atPath($constraint->firstField)
            ->assertRaised()
        ;
    }

    public function testValidateEmptySecondValue(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime());
        $object->method('getEnd')->willReturn(null);

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->secondField)
            ->setParameter('%field%', $constraint->firstField)
            ->atPath($constraint->secondField)
            ->assertRaised()
        ;
    }

    public function testValidateDatesInvalid(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime('2015-02-01 10:00'));
        $object->method('getEnd')->willReturn(new DateTime('2015-01-01 10:00'));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('%firstField%', $constraint->firstField)
            ->setParameter('%secondField%', $constraint->secondField)
            ->atPath($constraint->secondField)
            ->assertRaised()
        ;
    }

    public function testValidateDatesValid(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime('2015-01-01 10:00'));
        $object->method('getEnd')->willReturn(new DateTime('2015-02-01 10:00'));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateEqualDate(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime('2015-01-01 10:00'));
        $object->method('getEnd')->willReturn(new DateTime('2015-01-01 10:00'));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNotRequired(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(null);
        $object->method('getEnd')->willReturn(null);

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            ]
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNotRequiredWithEmptyFirst(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(null);
        $object->method('getEnd')->willReturn(new DateTime());

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNotRequiredWithEmptySecond(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock()
        ;
        $object->method('getBegin')->willReturn(new DateTime());
        $object->method('getEnd')->willReturn(null);

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new DateAfterValidator();
    }
}
