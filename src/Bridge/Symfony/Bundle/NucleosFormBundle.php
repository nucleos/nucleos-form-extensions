<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Bridge\Symfony\Bundle;

use Nucleos\Form\Bridge\Symfony\DependencyInjection\NucleosFormExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NucleosFormBundle extends Bundle
{
    public function getPath(): string
    {
        return __DIR__.'/..';
    }

    protected function getContainerExtensionClass(): string
    {
        return NucleosFormExtension::class;
    }
}
