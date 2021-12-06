<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GenderType extends AbstractType
{
    public const TYPE_MALE = 'm';

    public const TYPE_FEMALE = 'f';

    public const TYPE_NON_BINARY = 'd';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'male'       => static::TYPE_MALE,
                'female'     => static::TYPE_FEMALE,
                'non_binary' => static::TYPE_NON_BINARY,
            ],
            'choice_label' => static function ($value, $key, $index) {
                return 'gender.'.$key;
            },
            'choice_translation_domain' => 'NucleosFormBundle',
        ]);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'gender';
    }
}
