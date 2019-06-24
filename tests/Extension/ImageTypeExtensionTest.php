<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Extension;

use Core23\Form\Extension\ImageTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImageTypeExtensionTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $extension = new ImageTypeExtension();

        static::assertInstanceOf(FormTypeExtensionInterface::class, $extension);
    }

    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();

        $extension = new ImageTypeExtension();
        $extension->configureOptions($resolver);

        $result = $resolver->resolve();

        static::assertNull($result['image_path']);
    }

    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder->setAttribute('image_path', 'image')
            ->shouldBeCalled()
        ;

        $extension = new ImageTypeExtension();
        $extension->buildForm($builder->reveal(), [
            'image_path' => 'image',
        ]);
    }

    public function testBuildView(): void
    {
        $view = new FormView();

        $parentForm = $this->prophesize(FormInterface::class);
        $parentForm->getData()
            ->willReturn([
                'image' => '/foo/bar.png',
            ])
        ;

        $form = $this->prophesize(FormInterface::class);
        $form->getParent()
            ->willReturn($parentForm)
        ;

        $extension = new ImageTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'image_path' => '[image]',
        ]);

        static::assertSame('/foo/bar.png', $view->vars['image_url']);
    }

    public function testBuildViewWithoutData(): void
    {
        $view = new FormView();

        $parentForm = $this->prophesize(FormInterface::class);
        $parentForm->getData()
            ->willReturn(null)
        ;

        $form = $this->prophesize(FormInterface::class);
        $form->getParent()
            ->willReturn($parentForm)
        ;

        $extension = new ImageTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'image_path' => '[image]',
        ]);

        static::assertNull($view->vars['image_url']);
    }

    public function testExtendedTypes(): void
    {
        static::assertSame([FileType::class], ImageTypeExtension::getExtendedTypes());
    }

    public function testExtendedType(): void
    {
        $extension = new ImageTypeExtension();

        static::assertSame(FileType::class, $extension->getExtendedType());
    }
}
