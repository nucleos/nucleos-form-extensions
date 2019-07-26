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

final class DACHCountryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $countries = $this->getCountries();

        $resolver->setDefaults([
            'choices'      => array_combine($countries, $countries),
            'choice_label' => static function ($value, $key, $index) {
                return 'form.choice_'.strtolower($value);
            },
            'choice_translation_domain' => 'Core23FormBundle',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'core23_country';
    }

    /**
     * @return string[]
     */
    protected function getCountries(): array
    {
        return ['DE', 'AT', 'CH'];
    }
}
