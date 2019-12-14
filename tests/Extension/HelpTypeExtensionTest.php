<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Extension;

use Core23\Form\Extension\HelpTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class HelpTypeExtensionTest extends TestCase
{
    public function testBuildViewWithDefaults(): void
    {
        $view = new FormView();
        $form = $this->prophesize(FormInterface::class);

        $extension = new HelpTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'help'                   => null,
            'help_translation_domain'=> null,
        ]);

        static::assertNull($view->vars['help']);
        static::assertNull($view->vars['help_translation_domain']);
    }

    public function testBuildViewWithParentTranslation(): void
    {
        $parentView = new FormView();

        $view                             = new FormView($parentView);
        $view->vars['translation_domain'] = 'Foo';
        $form                             = $this->prophesize(FormInterface::class);

        $extension = new HelpTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'help'                   => 'my help text',
            'help_translation_domain'=> null,
        ]);

        static::assertSame('my help text', $view->vars['help']);
        static::assertSame('Foo', $view->vars['help_translation_domain']);
    }

    public function testBuildView(): void
    {
        $view = new FormView();
        $form = $this->prophesize(FormInterface::class);

        $extension = new HelpTypeExtension();
        $extension->buildView($view, $form->reveal(), [
            'help'                   => null,
            'help_translation_domain'=> null,
        ]);

        static::assertNull($view->vars['help']);
        static::assertNull($view->vars['help_translation_domain']);
    }

    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();

        $extension = new HelpTypeExtension();
        $extension->configureOptions($resolver);

        $result = $resolver->resolve();

        static::assertNull($result['help']);
        static::assertNull($result['help_translation_domain']);
    }

    public function testConfigureOptionsWithFallback(): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('translation_domain', 'FallbackDomain');

        $extension = new HelpTypeExtension();
        $extension->configureOptions($resolver);

        $result = $resolver->resolve();

        static::assertNull($result['help']);
        static::assertSame('FallbackDomain', $result['help_translation_domain']);
    }

    public function testExtendedTypes(): void
    {
        static::assertSame([FormType::class], HelpTypeExtension::getExtendedTypes());
    }
}
