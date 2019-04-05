<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Type;

use Sonata\Form\Type\DatePickerType as BaseDatePickerType;

/**
 * @deprecated use Sonata\Form\Type\DatePickerType instead
 */
final class DatePickerType extends BaseDatePickerType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'core23_type_date_picker';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseDatePickerType::class;
    }
}
