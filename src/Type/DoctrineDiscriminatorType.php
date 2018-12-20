<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Type;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class DoctrineDiscriminatorType extends AbstractType
{
    /**
     * @var BaseEntityManager
     */
    private $entityManager;

    /**
     * @param BaseEntityManager $manager
     */
    public function __construct(BaseEntityManager $manager)
    {
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = [];

        $meta = $this->entityManager->getEntityManager()->getClassMetadata($this->entityManager->getClass());

        if (\is_array($meta->discriminatorMap)) {
            foreach ($meta->discriminatorMap as $key => $class) {
                $choices[$key] = $key;
            }
        }

        $resolver->setDefaults([
            'choices' => $choices,
        ]);
    }
}
