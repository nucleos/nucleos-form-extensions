<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType as BaseAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class AutocompleteType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'property'    => null,
                'empty_value' => '',
            ])
            ->setRequired('route_name')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $text = null;

        if (null !== $data = $form->getData()) {
            if (null !== $options['property']) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $accessor->getValue($data, $options['property']);
            } else {
                $text = (string) $data;
            }
        }

        $view->vars['text']        = $text;
        $view->vars['route_name']  = $options['route_name'];
        $view->vars['empty_value'] = $options['empty_value'];
    }

    public function getBlockPrefix(): string
    {
        return 'nucleos_autocomplete';
    }

    public function getParent(): ?string
    {
        return BaseAutocompleteType::class;
    }
}
