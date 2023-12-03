<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Extension;

use Nucleos\Form\Extension\DownloadTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DownloadTypeExtensionTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();

        $extension = new DownloadTypeExtension();
        $extension->configureOptions($resolver);

        $result = $resolver->resolve();

        self::assertNull($result['download_path']);
        self::assertSame('link_download', $result['download_text']);
    }

    public function testBuildForm(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::once())->method('setAttribute')
            ->with('download_path', 'image')
        ;

        $extension = new DownloadTypeExtension();
        $extension->buildForm($builder, [
            'download_path' => 'image',
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

        $extension = new DownloadTypeExtension();
        $extension->buildView($view, $form, [
            'download_path' => '[image]',
            'download_text' => 'link_download',
        ]);

        self::assertSame('/foo/bar.png', $view->vars['download_path']);
        self::assertSame('link_download', $view->vars['download_text']);
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

        $extension = new DownloadTypeExtension();
        $extension->buildView($view, $form, [
            'download_path' => '[image]',
            'download_text' => 'link_download',
        ]);

        self::assertNull($view->vars['download_path']);
        self::assertSame('link_download', $view->vars['download_text']);
    }

    public function testExtendedTypes(): void
    {
        self::assertSame([FileType::class], DownloadTypeExtension::getExtendedTypes());
    }
}
