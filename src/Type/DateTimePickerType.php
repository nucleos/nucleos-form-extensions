<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Type;

use Sonata\Form\Type\DateTimePickerType as BaseDateTimePickerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTimePickerType extends BaseDateTimePickerType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'dp_side_by_side' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'core23_type_datetime_picker';
    }

    public function getParent()
    {
        return BaseDateTimePickerType::class;
    }
}
