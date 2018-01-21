<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Tests\Validator\Constraints;

use Core23\FormExtensionsBundle\Form\Model\BatchTime;
use Core23\FormExtensionsBundle\Validator\Constraints\BatchTimeAfter;
use Core23\FormExtensionsBundle\Validator\Constraints\BatchTimeAfterValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class BatchTimeAfterValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidFirstField(): void
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue('test'));

        $constraint = new BatchTimeAfter(
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
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue('test'));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            [
                'firstField'  => 'begin',
                'secondField' => 'end',
            ]
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateEmptyFirstValue(): void
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-02-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-01-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-02-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-01-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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

        $constraint = new BatchTimeAfter(
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
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new BatchTimeAfter(
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

    public function testValidateEmptyFirstValueDate(): void
    {
        $begin = new BatchTime();

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-02-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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

    public function testValidateEmptySecondValueDate(): void
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(['getBegin', 'getEnd'])
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
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

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new BatchTimeAfterValidator();
    }
}
