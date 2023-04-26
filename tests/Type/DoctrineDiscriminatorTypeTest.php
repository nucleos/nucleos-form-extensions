<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Nucleos\Form\Tests\Fixtures\BarEntity;
use Nucleos\Form\Tests\Fixtures\FooEntity;
use Nucleos\Form\Type\DoctrineDiscriminatorType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

final class DoctrineDiscriminatorTypeTest extends BaseTypeTestCase
{
    /**
     * @var ManagerRegistry&MockObject
     */
    private ManagerRegistry $managerRegistry;

    /**
     * @var MockObject&ObjectManager
     */
    private ObjectManager $objectManager;

    /**
     * @var ClassMetadata&MockObject
     */
    private ClassMetadata $classMetadata;

    protected function setUp(): void
    {
        $this->classMetadata = $this->createMock(ClassMetadata::class);

        $this->classMetadata->discriminatorMap = [
            'foo' => FooEntity::class,
            'bar' => BarEntity::class,
        ];

        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->objectManager->method('getClassMetadata')->with('MyEntityClass')
            ->willReturn($this->classMetadata)
        ;

        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->managerRegistry->method('getManagerForClass')->with('MyEntityClass')
            ->willReturn($this->objectManager)
        ;

        parent::setUp();
    }

    public function testTypesAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType(), null, [
            'class' => 'MyEntityClass',
        ])
            ->createView()->vars['choices'];

        static::assertContainsEquals(new ChoiceView('foo', 'foo', 'foo'), $choices);
        static::assertContainsEquals(new ChoiceView('bar', 'bar', 'bar'), $choices);
    }

    public function testPassIdAndNameToViewWithGrandParent(): void
    {
        $builder = $this->factory->createNamedBuilder('parent', FormType::class)
            ->add('child', FormType::class)
        ;
        $builder->get('child')->add('grand_child', $this->getTestedType(), [
            'class' => 'MyEntityClass',
        ]);
        $view = $builder->getForm()->createView();

        static::assertSame('parent_child_grand_child', $view['child']['grand_child']->vars['id']);
        static::assertSame('grand_child', $view['child']['grand_child']->vars['name']);
        static::assertSame('parent[child][grand_child]', $view['child']['grand_child']->vars['full_name']);
    }

    public function testUnknownTypeIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'types', [
            'class' => 'MyEntityClass',
        ])
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
        $builder = $this->factory->createBuilder($this->getTestedType(), null, [
            'class' => 'MyEntityClass',
        ]);

        if ($builder->getCompound()) {
            $emptyData = [];
            foreach ($builder as $field) {
                // empty children should map null (model data) in the compound view data
                $emptyData[$field->getName()] = null;
            }
        } else {
            // simple fields share the view and the model format, unless they use a transformer
            $expectedData = $emptyData;
        }

        $form = $builder->setEmptyData($emptyData)->getForm()->submit(null);

        static::assertSame($emptyData, $form->getViewData());
        static::assertSame($expectedData, $form->getNormData());
        static::assertSame($expectedData, $form->getData());
    }

    protected function create(mixed $data = null, array $options = []): FormInterface
    {
        return parent::create($data, array_merge([
            'class' => 'MyEntityClass',
        ], $options));
    }

    protected function createNamed(string $name, mixed $data = null, array $options = []): FormInterface
    {
        return parent::createNamed($name, $data, array_merge([
            'class' => 'MyEntityClass',
        ], $options));
    }

    protected function createBuilder(array $parentOptions = [], array $childOptions = []): FormBuilderInterface
    {
        return parent::createBuilder($parentOptions, array_merge([
            'class' => 'MyEntityClass',
        ], $childOptions));
    }

    protected function getTestedType(): string
    {
        return DoctrineDiscriminatorType::class;
    }

    /**
     * @return FormTypeInterface[]
     */
    protected function getTypes(): array
    {
        return [
            new DoctrineDiscriminatorType($this->managerRegistry),
        ];
    }
}
