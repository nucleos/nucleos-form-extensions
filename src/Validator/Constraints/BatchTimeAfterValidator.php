<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensions\Validator\Constraints;

use Core23\FormExtensions\Form\Model\BatchTime;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class BatchTimeAfterValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof BatchTimeAfter) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\BatchTimeAfter');
        }

        if (!is_object($value)) {
            return;
        }

        try {
            $firstDate = $this->getFieldValue($value, $constraint->firstField);
        } catch (NoSuchPropertyException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        try {
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

        if (!$firstDate instanceof BatchTime || !$secondDate instanceof BatchTime) {
            throw new UnexpectedTypeException($value, BatchTime::class);
        }

        if ($secondDate && !$firstDate->getTime()) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->firstField)
                ->setParameter('%field%', $constraint->secondField)
                ->atPath($constraint->firstField)
                ->addViolation();

            return;
        } elseif ($firstDate && !$secondDate->getTime()) {
            $this->context
                ->buildViolation($constraint->emptyMessage)
                ->setParameter('%emptyField%', $constraint->secondField)
                ->setParameter('%field%', $constraint->firstField)
                ->atPath($constraint->secondField)
                ->addViolation();

            return;
        }

        if ($firstDate->getSeconds() > $secondDate->getSeconds()) {
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
