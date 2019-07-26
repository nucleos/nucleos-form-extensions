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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

final class DownloadTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'download_path' => null,
                'download_text' => 'link_download',
            ])
            ->setAllowedTypes('download_path', ['null', 'string'])
            ->setAllowedTypes('download_text', ['null', 'string'])
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null !== $options['download_path']) {
            $builder->setAttribute('download_path', $options['download_path']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (null !== $options['download_path'] && null !== $form->getParent()) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $propertyPath     = new PropertyPath($options['download_path']);

                $downloadPath = $propertyAccessor->getValue($parentData, $propertyPath);
            } else {
                $downloadPath = null;
            }
            // set an "download_path" variable that will be available when rendering this field
            $view->vars['download_path'] = $downloadPath;
        }
        $view->vars['download_text'] = $options['download_text'];
    }

    public function getExtendedType()
    {
        foreach (static::getExtendedTypes() as $extendedType) {
            return $extendedType;
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FileType::class,
        ];
    }
}
