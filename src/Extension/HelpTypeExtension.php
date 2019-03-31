<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated Use the symfony help extension
 * @see https://symfony.com/doc/current/form/form_customization.html#form-help-form-view
 */
final class HelpTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $helpTranslationDomain = $options['help_translation_domain'];
        if ($view->parent && null === $helpTranslationDomain) {
            $helpTranslationDomain = $view->vars['translation_domain'];
        }

        $view->vars = array_replace($view->vars, [
            'help'                    => $options['help'],
            'help_translation_domain' => $helpTranslationDomain,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $helpTranslationDomainNormalizer = function (Options $options, $helpTranslationDomain) {
            if (true === $helpTranslationDomain) {
                return $options['translation_domain'];
            }

            return $helpTranslationDomain;
        };

        $resolver->setDefaults([
            'help'                    => null,
            'help_translation_domain' => true,
        ]);

        $resolver->setNormalizer('help_translation_domain', $helpTranslationDomainNormalizer);

        $resolver->setAllowedTypes('help', ['null', 'string']);
        $resolver->setAllowedTypes('help_translation_domain', ['null', 'bool', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        foreach (static::getExtendedTypes() as $extendedType) {
            return $extendedType;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }
}
