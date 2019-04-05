<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Type;

use Core23\Form\Model\BatchTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BatchTimeType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     */
    public function __construct(string $class = null)
    {
        if (null === $class) {
            $class = BatchTime::class;
        } else {
            @trigger_error(
                'Passing a class as constructor argument is deprecated',
                E_USER_DEPRECATED
            );
        }

        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'batch_time';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'compound'   => true,
        ]);
    }
}
