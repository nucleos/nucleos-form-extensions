<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use Nucleos\Form\Model\BatchTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BatchTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $emptyData = $builder->getEmptyData() ?: null;

        $builder
            ->add('day', ChoiceType::class, [
                'label'                     => 'form.input_batch_time_day',
                'choices'                   => range(0, 2),
                'choice_translation_domain' => false,
                'required'                  => $options['required'],
                'empty_data'                => $emptyData instanceof BatchTime ? $emptyData->getDay() : null,
            ])
            ->add('time', TimePickerType::class, [
                'label'                     => 'form.input_batch_time_time',
                'required'                  => $options['required'],
                'empty_data'                => $emptyData instanceof BatchTime ? $emptyData->getTime() : null,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'batch_time';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BatchTime::class,
            'compound'   => true,
        ]);
    }
}
