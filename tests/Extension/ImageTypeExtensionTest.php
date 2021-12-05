<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Extension;

use Nucleos\Form\Extension\ImageTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImageTypeExtensionTest extends TestCase
{
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
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(static::once())->method('setAttribute')
            ->with('image_path', 'image')
        ;

        $extension = new ImageTypeExtension();
        $extension->buildForm($builder, [
            'image_path' => 'image',
        ]);
    }

    public function testBuildView(): void
    {
        $view = new FormView();

        $parentForm = $this->createMock(FormInterface::class);
        $parentForm->method('getData')
            ->willReturn([
                'image' => '/foo/bar.png',
            ])
        ;

        $form = $this->createMock(FormInterface::class);
        $form->method('getParent')
            ->willReturn($parentForm)
        ;

        $extension = new ImageTypeExtension();
        $extension->buildView($view, $form, [
            'image_path' => '[image]',
        ]);

        static::assertSame('/foo/bar.png', $view->vars['image_url']);
    }

    public function testBuildViewWithoutData(): void
    {
        $view = new FormView();

        $parentForm = $this->createMock(FormInterface::class);
        $parentForm->method('getData')
            ->willReturn(null)
        ;

        $form = $this->createMock(FormInterface::class);
        $form->method('getParent')
            ->willReturn($parentForm)
        ;

        $extension = new ImageTypeExtension();
        $extension->buildView($view, $form, [
            'image_path' => '[image]',
        ]);

        static::assertNull($view->vars['image_url']);
    }

    public function testExtendedTypes(): void
    {
        static::assertSame([FileType::class], ImageTypeExtension::getExtendedTypes());
    }
}
