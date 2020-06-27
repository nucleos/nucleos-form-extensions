<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Extension;

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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $helpTranslationDomainNormalizer = static function (Options $options, $helpTranslationDomain) {
            if (null === $helpTranslationDomain && $options->offsetExists('translation_domain')) {
                return $options->offsetGet('translation_domain');
            }

            return $helpTranslationDomain;
        };

        $resolver->setDefaults([
            'help'                    => null,
            'help_translation_domain' => null,
        ]);

        $resolver->setNormalizer('help_translation_domain', $helpTranslationDomainNormalizer);

        $resolver->setAllowedTypes('help', ['null', 'string']);
        $resolver->setAllowedTypes('help_translation_domain', ['null', 'bool', 'string']);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }
}
