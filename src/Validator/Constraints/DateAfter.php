<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 *
 * @Target({"CLASS", "ANNOTATION"})
 */
final class DateAfter extends Constraint
{
    /**
     * @var string
     */
    public $firstField;

    /**
     * @var string
     */
    public $secondField;

    /**
     * @var string
     */
    public $message = 'error.second_date_before_first';

    /**
     * @var string
     */
    public $emptyMessage = 'error.date_part_empty';

    /**
     * @var bool
     */
    public $required = true;

    /**
     * @param array $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if ($this->firstField === $this->secondField) {
            throw new InvalidOptionsException('The options "firstField" and "secondField" can not be the same for constraint '.__CLASS__, [
                'firstField',
                'secondField',
            ]);
        }
    }

    public function getRequiredOptions(): array
    {
        return [
            'firstField',
            'secondField',
        ];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
