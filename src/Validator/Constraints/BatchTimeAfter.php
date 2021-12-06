<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 *
 * @Target({"CLASS", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class BatchTimeAfter extends Constraint
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
    public $message = 'error.second_batch_time_before_first';

    /**
     * @var string
     */
    public $emptyMessage = 'error.batch_time_part_empty';

    /**
     * @var bool
     */
    public $required = true;

    public function __construct(mixed $options = null)
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

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
