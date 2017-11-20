<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'image_path' => null,
            ))
            ->setAllowedTypes('image_path', array('null', 'string'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null !== $options['image_path']) {
            $builder->setAttribute('image_path', $options['image_path']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['image_path'] !== null) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $propertyPath     = new PropertyPath($options['image_path']);

                $imageUrl = $propertyAccessor->getValue($parentData, $propertyPath);
            } else {
                $imageUrl = null;
            }
            $view->vars['image_url'] = $imageUrl;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FileType::class;
    }
}
