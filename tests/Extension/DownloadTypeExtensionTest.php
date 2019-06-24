<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Extension;

use Core23\Form\Extension\DownloadTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DownloadTypeExtensionTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $extension = new DownloadTypeExtension();

        static::assertInstanceOf(FormTypeExtensionInterface::class, $extension);
    }

    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();

        $extension = new DownloadTypeExtension();
        $extension->configureOptions($resolver);

        $result = $resolver->resolve();

        static::assertNull($result['download_path']);
        static::assertSame('link_download', $result['download_text']);
    }

    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder->setAttribute('download_path', 'image')
            ->shouldBeCalled()
        ;

        $extension = new DownloadTypeExtension();
        $extension->buildForm($builder->reveal(), [
            'download_path' => 'image',
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

        $extension = new DownloadTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'download_path' => '[image]',
            'download_text' => 'link_download',
        ]);

        static::assertSame('/foo/bar.png', $view->vars['download_path']);
        static::assertSame('link_download', $view->vars['download_text']);
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

        $extension = new DownloadTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'download_path' => '[image]',
            'download_text' => 'link_download',
        ]);

        static::assertNull($view->vars['download_path']);
        static::assertSame('link_download', $view->vars['download_text']);
    }

    public function testExtendedTypes(): void
    {
        static::assertSame([FileType::class], DownloadTypeExtension::getExtendedTypes());
    }

    public function testExtendedType(): void
    {
        $extension = new DownloadTypeExtension();

        static::assertSame(FileType::class, $extension->getExtendedType());
    }
}
