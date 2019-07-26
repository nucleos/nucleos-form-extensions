<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Validator\Constraints;

use DateTime;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class DateAfterValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateAfter) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\DateAfter');
        }

        if (!\is_object($value)) {
            throw new InvalidArgumentException(sprintf('Could not validate "%s"', \gettype($value)));
        }

        $firstFieldName  = $constraint->firstField;
        $secondFieldName = $constraint->secondField;

        $firstDate  = $this->getFieldValue($value, $firstFieldName);
        $secondDate = $this->getFieldValue($value, $secondFieldName);

        if (!$constraint->required && null === $firstDate && null === $secondDate) {
            return;
        }

        if (null === $firstDate && $secondDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $firstFieldName)
                ->setParameter('%field%', $secondFieldName)
                ->atPath($firstFieldName)
                ->addViolation()
            ;

            return;
        }

        if ($firstDate && null === $secondDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $secondFieldName)
                ->setParameter('%field%', $firstFieldName)
                ->atPath($secondFieldName)
                ->addViolation()
            ;

            return;
        }

        if ($firstDate > $secondDate) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%firstField%', $firstFieldName)
                ->setParameter('%secondField%', $secondFieldName)
                ->atPath($secondFieldName)
                ->addViolation()
            ;
        }
    }

    private function getFieldValue($object, string $field): ?DateTime
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        try {
            $value = $propertyAccessor->getValue($object, $field);
        } catch (NoSuchPropertyException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        if (null !== $value && !$value instanceof DateTime) {
            throw new UnexpectedTypeException($value, DateTime::class);
        }

        return $value;
    }
}
