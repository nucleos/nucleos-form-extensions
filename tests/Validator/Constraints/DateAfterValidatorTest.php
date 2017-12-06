<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Tests\Validator\Constraints;

use Core23\FormExtensionsBundle\Validator\Constraints\DateAfter;
use Core23\FormExtensionsBundle\Validator\Constraints\DateAfterValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

if (!class_exists(ConstraintValidatorTestCase::class)) {
    class_alias(
        'Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest',
        'Symfony\Component\Validator\Test\ConstraintValidatorTestCase'
    );
}

class DateAfterValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidFirstField(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidSecondField(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateInvalidFirstValue(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue('test'));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateInvalidSecondValue(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue('test'));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

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
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

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
            ->assertRaised();
    }

    public function testValidateEmptySecondValue(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

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
            ->assertRaised();
    }

    public function testValidateDatesInvalid(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-02-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));

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
            ->assertRaised();
    }

    public function testValidateDatesValid(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-02-01 10:00')));

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
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));

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
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

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
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->firstField)
            ->setParameter('%field%', $constraint->secondField)
            ->atPath($constraint->firstField)
            ->assertRaised();
    }

    public function testValidateNotRequiredWithEmptySecond(): void
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new DateAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            ]
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->secondField)
            ->setParameter('%field%', $constraint->firstField)
            ->atPath($constraint->secondField)
            ->assertRaised();
    }

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new DateAfterValidator();
    }
}
