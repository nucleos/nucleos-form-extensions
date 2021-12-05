<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use NumberFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NumberOutputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addViewTransformer(
            new NumberToLocalizedStringTransformer(
                $options['precision'],
                $options['grouping'],
                $options['rounding_mode']
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'precision'     => null,
                'grouping'      => false,
                'rounding_mode' => NumberFormatter::ROUND_HALFUP,
                'suffix'        => '',
                'prefix'        => '',
                'required'      => false,
                'disabled'      => true,
            ])
            ->setAllowedTypes('precision', ['null', 'int'])
            ->setAllowedTypes('grouping', 'bool')
            ->setAllowedTypes('suffix', 'string')
            ->setAllowedTypes('prefix', 'string')
            ->setAllowedValues('rounding_mode', [
                NumberFormatter::ROUND_FLOOR,
                NumberFormatter::ROUND_DOWN,
                NumberFormatter::ROUND_HALFDOWN,
                NumberFormatter::ROUND_HALFEVEN,
                NumberFormatter::ROUND_HALFUP,
                NumberFormatter::ROUND_UP,
                NumberFormatter::ROUND_CEILING,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'number_output';
    }

    public function getParent(): ?string
    {
        return OutputType::class;
    }
}
