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

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        array $options = [],
        string $firstField = null,
        string $secondField = null,
        string $message = null,
        string $emptyMessage = null,
        bool $required = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        if (null !== $firstField) {
            $options['firstField'] = $firstField;
        }
        if (null !== $secondField) {
            $options['secondField'] = $secondField;
        }

        parent::__construct($options, $groups, $payload);

        $this->firstField   = $firstField   ?? $this->firstField;
        $this->secondField  = $secondField  ?? $this->secondField;
        $this->message      = $message      ?? $this->message;
        $this->emptyMessage = $emptyMessage ?? $this->emptyMessage;
        $this->required     = $required     ?? $this->required;

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
