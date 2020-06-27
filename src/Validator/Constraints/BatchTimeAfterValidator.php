<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Validator\Constraints;

use Nucleos\Form\Model\BatchTime;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class BatchTimeAfterValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof BatchTimeAfter) {
            throw new UnexpectedTypeException($constraint, BatchTimeAfter::class);
        }

        if (!\is_object($value)) {
            throw new InvalidArgumentException(sprintf('Could not validate "%s"', \gettype($value)));
        }

        $firstDate  = $this->getFieldValue($value, $constraint->firstField);
        $secondDate = $this->getFieldValue($value, $constraint->secondField);

        if (!$constraint->required && null === $firstDate && null === $secondDate) {
            return;
        }

        if (null === $firstDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->firstField)
                ->setParameter('%field%', $constraint->secondField)
                ->atPath($constraint->firstField)
                ->addViolation()
            ;
        }

        if (null === $secondDate) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->secondField)
                ->setParameter('%field%', $constraint->firstField)
                ->atPath($constraint->secondField)
                ->addViolation()
            ;
        }

        if (null === $firstDate || null === $secondDate) {
            return;
        }

        if (null === $firstDate->getTime()) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->firstField)
                ->setParameter('%field%', $constraint->secondField)
                ->atPath($constraint->firstField)
                ->addViolation()
            ;

            return;
        }
        if (null === $secondDate->getTime()) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->secondField)
                ->setParameter('%field%', $constraint->firstField)
                ->atPath($constraint->secondField)
                ->addViolation()
            ;

            return;
        }

        if ($firstDate->getSeconds() > $secondDate->getSeconds()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%firstField%', $constraint->firstField)
                ->setParameter('%secondField%', $constraint->secondField)
                ->atPath($constraint->secondField)
                ->addViolation()
            ;
        }
    }

    /**
     * @param array|object $object
     */
    private function getFieldValue($object, string $field): ?BatchTime
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        try {
            $value =  $propertyAccessor->getValue($object, $field);
        } catch (NoSuchPropertyException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        if (null !== $value && !$value instanceof BatchTime) {
            throw new UnexpectedTypeException($value, BatchTime::class);
        }

        return $value;
    }
}
