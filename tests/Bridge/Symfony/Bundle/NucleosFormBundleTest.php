<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Bridge\Symfony\Bundle;

use Nucleos\Form\Bridge\Symfony\Bundle\NucleosFormBundle;
use Nucleos\Form\Bridge\Symfony\DependencyInjection\NucleosFormExtension;
use PHPUnit\Framework\TestCase;

final class NucleosFormBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        $bundle = new NucleosFormBundle();

        static::assertStringEndsWith('Bridge/Symfony/Bundle', \dirname($bundle->getPath()));
    }

    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosFormBundle();

        static::assertInstanceOf(NucleosFormExtension::class, $bundle->getContainerExtension());
    }
}
