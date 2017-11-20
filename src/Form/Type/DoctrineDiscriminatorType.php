<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Type;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class DoctrineDiscriminatorType extends AbstractType
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var BaseEntityManager
     */
    private $entityManager;

    /**
     * DoctrineDiscriminatorType constructor.
     *
     * @param string            $name
     * @param BaseEntityManager $manager
     */
    public function __construct($name, BaseEntityManager $manager)
    {
        $this->name          = $name;
        $this->entityManager = $manager;
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
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array();

        $meta = $this->entityManager->getEntityManager()->getClassMetadata($this->entityManager->getClass());

        foreach ($meta->discriminatorMap as $key => $class) {
            $choices[$key] = $key;
        }

        $resolver->setDefaults(array(
            'choices' => $choices,
        ));
    }
}
