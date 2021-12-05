<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nucleos\Form\Tests\Fixtures\EntityDoctrineDiscriminatorType;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\Doctrine\Entity\BaseEntityManager;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

final class DoctrineDiscriminatorTypeTest extends BaseTypeTest
{
    /**
     * @var BaseEntityManager&MockObject
     */
    private BaseEntityManager $baseEntityManager;

    /**
     * @var EntityManager&MockObject
     */
    private EntityManager $entityManager;

    /**
     * @var ClassMetadata&MockObject
     */
    private ClassMetadata $classMetadata;

    protected function setUp(): void
    {
        $this->classMetadata = $this->createMock(ClassMetadata::class);

        $this->classMetadata->discriminatorMap = [
            'foo' => 'FooEntity',
            'bar' => 'BarEntity',
        ];

        $this->entityManager = $this->createMock(EntityManager::class);
        $this->entityManager->method('getClassMetadata')->with('MyEntityClass')
            ->willReturn($this->classMetadata)
        ;

        $this->baseEntityManager = $this->createMock(BaseEntityManager::class);
        $this->baseEntityManager->method('getClass')
            ->willReturn('MyEntityClass')
        ;
        $this->baseEntityManager->method('getEntityManager')
            ->willReturn($this->entityManager)
        ;

        parent::setUp();
    }

    public function testTypesAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices'];

        static::assertContainsEquals(new ChoiceView('foo', 'foo', 'foo'), $choices);
        static::assertContainsEquals(new ChoiceView('bar', 'bar', 'bar'), $choices);
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
            new EntityDoctrineDiscriminatorType($this->baseEntityManager),
        ];
    }
}
