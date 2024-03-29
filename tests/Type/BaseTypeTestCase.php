<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class BaseTypeTestCase extends TypeTestCase
{
    public function testPassDisabledAsOption(): void
    {
        $form = $this->create(null, ['disabled' => true]);

        self::assertTrue($form->isDisabled());
    }

    public function testPassIdAndNameToView(): void
    {
        $view = $this->createNamed('name')
            ->createView()
        ;

        self::assertSame('name', $view->vars['id']);
        self::assertSame('name', $view->vars['name']);
        self::assertSame('name', $view->vars['full_name']);
    }

    public function testStripLeadingUnderscoresAndDigitsFromId(): void
    {
        $view = $this->createNamed('_09name')
            ->createView()
        ;

        self::assertSame('name', $view->vars['id']);
        self::assertSame('_09name', $view->vars['name']);
        self::assertSame('_09name', $view->vars['full_name']);
    }

    public function testPassIdAndNameToViewWithParent(): void
    {
        $view = $this->createBuilder()
            ->getForm()
            ->createView()
        ;

        self::assertSame('parent_child', $view['child']->vars['id']);
        self::assertSame('child', $view['child']->vars['name']);
        self::assertSame('parent[child]', $view['child']->vars['full_name']);
    }

    public function testPassIdAndNameToViewWithGrandParent(): void
    {
        $builder = $this->factory->createNamedBuilder('parent', FormType::class)
            ->add('child', FormType::class)
        ;
        $builder->get('child')->add('grand_child', $this->getTestedType());
        $view = $builder->getForm()->createView();

        self::assertSame('parent_child_grand_child', $view['child']['grand_child']->vars['id']);
        self::assertSame('grand_child', $view['child']['grand_child']->vars['name']);
        self::assertSame('parent[child][grand_child]', $view['child']['grand_child']->vars['full_name']);
    }

    public function testPassTranslationDomainToView(): void
    {
        $view = $this->create(null, [
            'translation_domain' => 'domain',
        ])
            ->createView()
        ;

        self::assertSame('domain', $view->vars['translation_domain']);
    }

    public function testInheritTranslationDomainFromParent(): void
    {
        $view = $this->createBuilder([
            'translation_domain' => 'domain',
        ])
            ->getForm()
            ->createView()
        ;

        self::assertSame('domain', $view['child']->vars['translation_domain']);
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

        self::assertSame('domain', $view['child']->vars['translation_domain']);
    }

    public function testDefaultTranslationDomain(): void
    {
        $view = $this->createBuilder()
            ->getForm()
            ->createView()
        ;

        self::assertNull($view['child']->vars['translation_domain']);
    }

    public function testPassLabelToView(): void
    {
        $view = $this->createNamed('__test___field', null, ['label' => 'My label'])
            ->createView()
        ;

        self::assertSame('My label', $view->vars['label']);
    }

    public function testPassMultipartFalseToView(): void
    {
        $view = $this->create()
            ->createView()
        ;

        self::assertFalse($view->vars['multipart']);
    }

    /**
     * @param mixed|null $expected
     * @param mixed|null $norm
     * @param mixed|null $view
     */
    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        $form = $this->create();
        $form->submit(null);

        self::assertSame($expected, $form->getData());
        self::assertSame($norm, $form->getNormData());
        self::assertSame($view, $form->getViewData());
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

        self::assertSame($emptyData, $form->getViewData());
        self::assertSame($expectedData, $form->getNormData());
        self::assertSame($expectedData, $form->getData());
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function create(mixed $data = null, array $options = []): FormInterface
    {
        return $this->factory->create($this->getTestedType(), $data, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function createNamed(string $name, mixed $data = null, array $options = []): FormInterface
    {
        return $this->factory->createNamed($name, $this->getTestedType(), $data, $options);
    }

    /**
     * @param array<string, mixed> $parentOptions
     * @param array<string, mixed> $childOptions
     */
    protected function createBuilder(array $parentOptions = [], array $childOptions = []): FormBuilderInterface
    {
        return $this->factory
            ->createNamedBuilder('parent', FormType::class, null, $parentOptions)
            ->add('child', $this->getTestedType(), $childOptions)
        ;
    }

    /**
     * @return class-string<FormTypeInterface<mixed>>
     */
    abstract protected function getTestedType(): string;
}
