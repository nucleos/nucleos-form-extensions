<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchTimeType extends AbstractType
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = $builder->getForm()->isRequired();

        $builder
            ->add('day', ChoiceType::class, array(
                'label'                     => 'form.input_batch_time_day',
                'choices'                   => range(0, 2),
                'choice_translation_domain' => false,
                'required'                  => $required,
            ))
            ->add('time', TimePickerType::class, array(
                'label'    => 'form.input_batch_time_time',
                'required' => $required,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }
}
