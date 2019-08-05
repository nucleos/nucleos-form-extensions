<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OutputType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ('' === $options['empty_data']) {
            $builder->addViewTransformer($this);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound'  => false,
            'required'  => false,
            'disabled'  => true,
        ]);
    }

    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data): string
    {
        return $data ?? '';
    }

    public function getBlockPrefix(): string
    {
        return 'output';
    }
}
