<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

abstract class BaseTypeTest extends TypeTestCase
{
    public function testPassDisabledAsOption(): void
    {
        $form = $this->create(null, ['disabled' => true]);

        static::assertTrue($form->isDisabled());
    }

    public function testPassIdAndNameToView(): void
    {
        $view = $this->createNamed('name')
            ->createView()
        ;

        static::assertSame('name', $view->vars['id']);
        static::assertSame('name', $view->vars['name']);
        static::assertSame('name', $view->vars['full_name']);
    }

    public function testStripLeadingUnderscoresAndDigitsFromId(): void
    {
        $view = $this->createNamed('_09name')
            ->createView()
        ;

        static::assertSame('name', $view->vars['id']);
        static::assertSame('_09name', $view->vars['name']);
        static::assertSame('_09name', $view->vars['full_name']);
    }

    public function testPassIdAndNameToViewWithParent(): void
    {
        $view = $this->createBuilder()
            ->getForm()
            ->createView()
        ;

        static::assertSame('parent_child', $view['child']->vars['id']);
        static::assertSame('child', $view['child']->vars['name']);
        static::assertSame('parent[child]', $view['child']->vars['full_name']);
    }

    public function testPassIdAndNameToViewWithGrandParent(): void
    {
        $builder = $this->factory->createNamedBuilder('parent', FormType::class)
            ->add('child', FormType::class)
        ;
        $builder->get('child')->add('grand_child', $this->getTestedType());
        $view = $builder->getForm()->createView();

        static::assertSame('parent_child_grand_child', $view['child']['grand_child']->vars['id']);
        static::assertSame('grand_child', $view['child']['grand_child']->vars['name']);
        static::assertSame('parent[child][grand_child]', $view['child']['grand_child']->vars['full_name']);
    }

    public function testPassTranslationDomainToView(): void
    {
        $view = $this->create(null, [
            'translation_domain' => 'domain',
        ])
            ->createView()
        ;

        static::assertSame('domain', $view->vars['translation_domain']);
    }

    public function testInheritTranslationDomainFromParent(): void
    {
        $view = $this->createBuilder([
            'translation_domain' => 'domain',
        ])
            ->getForm()
            ->createView()
        ;

        static::assertSame('domain', $view['child']->vars['translation_domain']);
    }

    public function testPreferOwnTranslationDomain(): void
    {
        $view = $this->createBuilder([
            'translation_domain' => 'parent_domain',
        ], [
            'translation_domain' => 'domain',
        ])
            ->getForm()
            ->createView()
        ;

        static::assertSame('domain', $view['child']->vars['translation_domain']);
    }

    public function testDefaultTranslationDomain(): void
    {
        $view = $this->createBuilder()
            ->getForm()
            ->createView()
        ;

        static::assertNull($view['child']->vars['translation_domain']);
    }

    public function testPassLabelToView(): void
    {
        $view = $this->createNamed('__test___field', null, ['label' => 'My label'])
            ->createView()
        ;

        static::assertSame('My label', $view->vars['label']);
    }

    public function testPassMultipartFalseToView(): void
    {
        $view = $this->create()
            ->createView()
        ;

        static::assertFalse($view->vars['multipart']);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        $form = $this->create();
        $form->submit(null);

        static::assertSame($expected, $form->getData());
        static::assertSame($norm, $form->getNormData());
        static::assertSame($view, $form->getViewData());
    }

    /**
     * @param mixed|null $emptyData
     * @param mixed|null $expectedData
     */
    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'empty', $expectedData = null): void
    {
        $builder = $this->factory->createBuilder($this->getTestedType());

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

    /**
     * @param mixed|null $data
     */
    protected function create($data = null, array $options = []): FormInterface
    {
        return $this->factory->create($this->getTestedType(), $data, $options);
    }

    /**
     * @param mixed|null $data
     */
    protected function createNamed(string $name, $data = null, array $options = []): FormInterface
    {
        return $this->factory->createNamed($name, $this->getTestedType(), $data, $options);
    }

    protected function createBuilder(array $parentOptions = [], array $childOptions = []): FormBuilderInterface
    {
        return $this->factory
            ->createNamedBuilder('parent', FormType::class, null, $parentOptions)
            ->add('child', $this->getTestedType(), $childOptions)
        ;
    }

    abstract protected function getTestedType(): string;
}
