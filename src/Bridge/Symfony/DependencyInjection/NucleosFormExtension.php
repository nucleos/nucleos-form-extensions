<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NucleosFormExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.php');
        $loader->load('validator.php');

        /** @var array<string, mixed> $bundles */
        $bundles       = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $loader->load('form_doctrine.php');
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('twig')) {
            // add custom form widgets
            $container->prependExtensionConfig('twig', [
                'form_themes' => ['@NucleosForm/Form/widgets.html.twig'],
            ]);
        }
    }
}
