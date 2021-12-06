<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Bridge\Symfony\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\Form\Bridge\Symfony\DependencyInjection\NucleosFormExtension;
use Nucleos\Form\Extension\DownloadTypeExtension;
use Nucleos\Form\Extension\ImageTypeExtension;
use Nucleos\Form\Type\BatchTimeType;
use Nucleos\Form\Type\DACHCountryType;
use Nucleos\Form\Type\DateOutputType;
use Nucleos\Form\Type\GenderType;
use Nucleos\Form\Type\NumberOutputType;
use Nucleos\Form\Type\OutputType;
use Nucleos\Form\Type\TimePickerType;
use Nucleos\Form\Validator\Constraints\BatchTimeAfterValidator;
use Nucleos\Form\Validator\Constraints\DateAfterValidator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class NucleosFormExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->load();

        $this->assertContainerBuilderHasService(DACHCountryType::class);
        $this->assertContainerBuilderHasService(GenderType::class);
        $this->assertContainerBuilderHasService(OutputType::class);
        $this->assertContainerBuilderHasService(DateOutputType::class);
        $this->assertContainerBuilderHasService(NumberOutputType::class);
        $this->assertContainerBuilderHasService(BatchTimeType::class);
        $this->assertContainerBuilderHasService(TimePickerType::class);
        $this->assertContainerBuilderHasService(ImageTypeExtension::class);
        $this->assertContainerBuilderHasService(DownloadTypeExtension::class);

        $this->assertContainerBuilderHasService(DateAfterValidator::class);
        $this->assertContainerBuilderHasService(BatchTimeAfterValidator::class);
    }

    public function testLoadWithTwigExtension(): void
    {
        $fakeContainer = $this->createMock(ContainerBuilder::class);
        $fakeContainer->expects(static::once())
            ->method('hasExtension')
            ->with(static::equalTo('twig'))
            ->willReturn(true)
        ;
        $fakeContainer->expects(static::once())
            ->method('prependExtensionConfig')
            ->with('twig', ['form_themes' => ['@NucleosForm/Form/widgets.html.twig']])
        ;

        foreach ($this->getContainerExtensions() as $extension) {
            if ($extension instanceof PrependExtensionInterface) {
                $extension->prepend($fakeContainer);
            }
        }
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosFormExtension(),
        ];
    }
}
