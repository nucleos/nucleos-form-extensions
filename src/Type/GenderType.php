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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GenderType extends AbstractType
{
    public const TYPE_MALE = 'm';

    public const TYPE_FEMALE = 'f';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'male'   => static::TYPE_MALE,
                'female' => static::TYPE_FEMALE,
            ],
            'choice_label' => static function ($value, $key, $index) {
                return 'gender.'.$key;
            },
            'choice_translation_domain' => 'Core23FormBundle',
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'gender';
    }
}
