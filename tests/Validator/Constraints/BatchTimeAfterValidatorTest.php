<?php

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

class BatchTimeAfterValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidFirstField()
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidSecondField()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateInvalidFirstValue()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue('test'));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateInvalidSecondValue()
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue('test'));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateEmptyFirstValue()
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->firstField)
            ->setParameter('%field%', $constraint->secondField)
            ->atPath($constraint->firstField)
            ->assertRaised();
    }

    public function testValidateEmptySecondValue()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->secondField)
            ->setParameter('%field%', $constraint->firstField)
            ->atPath($constraint->secondField)
            ->assertRaised();
    }

    public function testValidateDatesInvalid()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-02-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-01-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('%firstField%', $constraint->firstField)
            ->setParameter('%secondField%', $constraint->secondField)
            ->atPath($constraint->secondField)
            ->assertRaised();
    }

    public function testValidateDatesValid()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-02-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateEqualDate()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-01-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNotRequired()
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            )
        );

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateNotRequiredWithEmptyFirst()
    {
        $end = new BatchTime();
        $end->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->firstField)
            ->setParameter('%field%', $constraint->secondField)
            ->atPath($constraint->firstField)
            ->assertRaised();
    }

    public function testValidateNotRequiredWithEmptySecond()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime());

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
                'required'    => false,
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->secondField)
            ->setParameter('%field%', $constraint->firstField)
            ->atPath($constraint->secondField)
            ->assertRaised();
    }

    public function testValidateEmptyFirstValueDate()
    {
        $begin = new BatchTime();

        $end = new BatchTime();
        $end->setTime(new \DateTime('2015-02-01 10:00'));

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->setPropertyPath('');

        $this->validator->validate($object, $constraint);

        $this->buildViolation($constraint->emptyMessage)
            ->setParameter('%emptyField%', $constraint->firstField)
            ->setParameter('%field%', $constraint->secondField)
            ->atPath($constraint->firstField)
            ->assertRaised();
    }

    public function testValidateEmptySecondValueDate()
    {
        $begin = new BatchTime();
        $begin->setTime(new \DateTime('2015-01-01 10:00'));

        $end = new BatchTime();

        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue($begin));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue($end));

        $constraint = new BatchTimeAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
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
