<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class BatchTimeAfter extends Constraint
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
     * @param array $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->firstField || null === $this->secondField) {
            throw new MissingOptionsException('The options "firstField" and "secondField" must be given for constraint '.__CLASS__, array('firstField', 'secondField'));
        } elseif ($this->firstField === $this->secondField) {
            throw new InvalidArgumentException('The options "firstField" and "secondField" can not be the same for constraint '.__CLASS__);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'core23.form.validator.batch_time_after';
    }
}
