<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class HelpTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $helpTranslationDomain = $options['help_translation_domain'];
        if ($view->parent && null === $helpTranslationDomain) {
            $helpTranslationDomain = $view->vars['translation_domain'];
        }

        $view->vars = array_replace($view->vars, array(
            'help'                    => $options['help'],
            'help_translation_domain' => $helpTranslationDomain,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $helpTranslationDomainNormalizer = function (Options $options, $helpTranslationDomain) {
            if (true === $helpTranslationDomain) {
                return $options['translation_domain'];
            }

            return $helpTranslationDomain;
        };

        $resolver->setDefaults(array(
            'help'                    => null,
            'help_translation_domain' => true,
        ));

        $resolver->setNormalizer('help_translation_domain', $helpTranslationDomainNormalizer);

        $resolver->setAllowedTypes('help', array('null', 'string'));
        $resolver->setAllowedTypes('help_translation_domain', array('null', 'bool', 'string'));
    }
}
