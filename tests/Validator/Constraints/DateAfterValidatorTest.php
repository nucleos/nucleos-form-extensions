<?php

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

class DateAfterValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateInvalidFirstField()
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue('test'));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue('test'));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
            array(
                'firstField'  => 'begin',
                'secondField' => 'end',
            )
        );

        $this->validator->validate($object, $constraint);
    }

    public function testValidateEmptyFirstValue()
    {
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-02-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-02-01 10:00')));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime('2015-01-01 10:00')));

        $constraint = new DateAfter(
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

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(null));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(new \DateTime()));

        $constraint = new DateAfter(
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
        $object = $this->getMockBuilder('stdClass')
            ->setMethods(array('getBegin', 'getEnd'))
            ->getMock();
        $object->expects($this->any())->method('getBegin')->will($this->returnValue(new \DateTime()));
        $object->expects($this->any())->method('getEnd')->will($this->returnValue(null));

        $constraint = new DateAfter(
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

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new DateAfterValidator();
    }
}
