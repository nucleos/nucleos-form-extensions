<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DoctrineDiscriminatorType extends AbstractType
{
    private ManagerRegistry $manager;

    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choice_loader'   => function (Options $options): ChoiceLoaderInterface {
                $class = $options['class'];

                return new CallbackChoiceLoader(fn (): array => $this->getChoices($class));
            },
        ]);

        $resolver->setRequired(['class']);
    }

    /**
     * @param class-string $class
     *
     * @return array<string, string>
     */
    private function getChoices(string $class): array
    {
        $choices = [];

        $manager = $this->manager->getManagerForClass($class);

        if (null === $manager) {
            return $choices;
        }

        $meta = $manager->getClassMetadata($class);

        \assert($meta instanceof ClassMetadataInfo);

        if (\is_array($meta->discriminatorMap)) {
            foreach ($meta->discriminatorMap as $key => $value) {
                $choices[$key] = $key;
            }
        }

        return $choices;
    }
}
