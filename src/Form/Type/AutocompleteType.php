<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Type;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType as BaseAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AutocompleteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'property' => null,
            ])
            ->setRequired('route_name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $text = null;

        if ($data = $form->getData()) {
            if ($options['property']) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $accessor->getValue($data, $options['property']);
            } else {
                $text = (string) $data;
            }
        }

        $view->vars['text']       = $text;
        $view->vars['route_name'] = $options['route_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'core23_autocomplete';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseAutocompleteType::class;
    }
}
