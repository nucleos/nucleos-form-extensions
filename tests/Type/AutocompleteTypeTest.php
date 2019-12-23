<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\AutocompleteType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType as PUGXAutocompleteType;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

final class AutocompleteTypeTest extends BaseTypeTest
{
    /**
     * @var ObjectProphecy
     */
    private $objectRepository;

    /**
     * @var ObjectProphecy
     */
    private $objectManager;

    /**
     * @var ObjectProphecy
     */
    private $registry;

    protected function setUp(): void
    {
        $this->objectRepository = $this->prophesize(ObjectRepository::class);

        $this->objectManager = $this->prophesize(ObjectManager::class);
        $this->objectManager->getRepository('FooEntity')
            ->willReturn($this->objectRepository)
        ;

        $this->registry = $this->prophesize(ManagerRegistry::class);
        $this->registry->getManagerForClass('FooEntity')
            ->willReturn($this->objectManager)
        ;

        parent::setUp();
    }

    public function testPassIdAndNameToViewWithGrandParent(): void
    {
        $builder = $this->factory->createNamedBuilder('parent', FormType::class)
            ->add('child', FormType::class)
        ;
        $builder->get('child')->add('grand_child', $this->getTestedType(), [
            'class'      => 'FooEntity',
            'route_name' => 'my-route',
        ]);
        $view = $builder->getForm()->createView();

        static::assertSame('parent_child_grand_child', $view['child']['grand_child']->vars['id']);
        static::assertSame('grand_child', $view['child']['grand_child']->vars['name']);
        static::assertSame('parent[child][grand_child]', $view['child']['grand_child']->vars['full_name']);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = ''): void
    {
        parent::testSubmitNull($expected, $norm, $view);
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 1, $expectedData = null): void
    {
        $model = new stdClass();

        $this->objectRepository->find(Argument::is(1))
            ->willReturn($model)
        ;

        $builder = $this->factory->createBuilder($this->getTestedType(), null, [
            'class'      => 'FooEntity',
            'route_name' => 'my-route',
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

        static::assertSame('1', $form->getViewData());
        static::assertSame($expectedData, $form->getNormData());
        static::assertSame($model, $form->getData());
    }

    protected function create($data = null, array $options = []): FormInterface
    {
        $options = array_merge([
            'class'      => 'FooEntity',
            'route_name' => 'my-route',
        ], $options);

        return $this->factory->create($this->getTestedType(), $data, $options);
    }

    protected function createNamed(string $name, $data = null, array $options = []): FormInterface
    {
        $options = array_merge([
            'class'      => 'FooEntity',
            'route_name' => 'my-route',
        ], $options);

        return $this->factory->createNamed($name, $this->getTestedType(), $data, $options);
    }

    protected function createBuilder(array $parentOptions = [], array $childOptions = []): FormBuilderInterface
    {
        $childOptions = array_merge([
            'class'      => 'FooEntity',
            'route_name' => 'my-route',
        ], $childOptions);

        return $this->factory
            ->createNamedBuilder('parent', FormType::class, null, $parentOptions)
            ->add('child', $this->getTestedType(), $childOptions)
        ;
    }

    protected function getTestedType(): string
    {
        return AutocompleteType::class;
    }

    protected function getTypes(): array
    {
        return [
            new PUGXAutocompleteType($this->registry->reveal()),
        ];
    }
}
