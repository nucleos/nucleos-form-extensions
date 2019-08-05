<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Tests\Fixtures\EntityDoctrineDiscriminatorType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sonata\Doctrine\Entity\BaseEntityManager;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

final class DoctrineDiscriminatorTypeTest extends BaseTypeTest
{
    private $baseEntityManager;

    private $entityManager;

    private $classMetadata;

    protected function setUp(): void
    {
        $this->classMetadata = $this->prophesize(ClassMetadata::class);

        $this->classMetadata->discriminatorMap = [
            'foo' => 'FooEntity',
            'bar' => 'BarEntity',
        ];

        $this->entityManager = $this->prophesize(EntityManager::class);
        $this->entityManager->getClassMetadata('MyEntityClass')
            ->willReturn($this->classMetadata)
        ;

        $this->baseEntityManager = $this->prophesize(BaseEntityManager::class);
        $this->baseEntityManager->getClass()
            ->willReturn('MyEntityClass')
        ;
        $this->baseEntityManager->getEntityManager()
            ->willReturn($this->entityManager)
        ;

        parent::setUp();
    }

    public function testTypesAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices'];

        static::assertContains(new ChoiceView('foo', 'foo', 'foo'), $choices, '', false, false);
        static::assertContains(new ChoiceView('bar', 'bar', 'bar'), $choices, '', false, false);
    }

    public function testUnknownTypeIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'types')
            ->createView()->vars['choices'];

        $countryCodes = [];

        foreach ($choices as $choice) {
            $countryCodes[] = $choice->value;
        }

        static::assertNotContains('baz', $countryCodes);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = ''): void
    {
        parent::testSubmitNull($expected, $norm, $view);
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'foo', $expectedData = 'foo'): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    protected function getTestedType(): string
    {
        return EntityDoctrineDiscriminatorType::class;
    }

    protected function getTypes(): array
    {
        return [
            new EntityDoctrineDiscriminatorType($this->baseEntityManager->reveal()),
        ];
    }
}
