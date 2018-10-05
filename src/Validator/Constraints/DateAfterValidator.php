<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Validator\Constraints;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class DateAfterValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateAfter) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\DateAfter');
        }

        if (!\is_object($value)) {
            return;
        }

        try {
            $firstDate  = $this->getFieldValue($value, $constraint->firstField);
            $secondDate = $this->getFieldValue($value, $constraint->secondField);
        } catch (NoSuchPropertyException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        if (!$constraint->required && !$firstDate && !$secondDate) {
            return;
        }

        if (!$firstDate && $secondDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->firstField)
                ->setParameter('%field%', $constraint->secondField)
                ->atPath($constraint->firstField)
                ->addViolation();

            return;
        } elseif ($firstDate && !$secondDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->secondField)
                ->setParameter('%field%', $constraint->firstField)
                ->atPath($constraint->secondField)
                ->addViolation();

            return;
        }

        if (!$firstDate instanceof \DateTime || !$secondDate instanceof \DateTime) {
            throw new UnexpectedTypeException($value, \DateTime::class);
        }

        if ($firstDate > $secondDate) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%firstField%', $constraint->firstField)
                ->setParameter('%secondField%', $constraint->secondField)
                ->atPath($constraint->secondField)
                ->addViolation();
        }
    }

    /**
     * @param mixed  $object
     * @param string $field
     *
     * @return mixed
     */
    private function getFieldValue($object, string $field)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($object, $field);
    }
}
