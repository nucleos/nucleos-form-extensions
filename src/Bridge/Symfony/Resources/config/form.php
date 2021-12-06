<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\Form\Extension\DownloadTypeExtension;
use Nucleos\Form\Extension\ImageTypeExtension;
use Nucleos\Form\Type\BatchTimeType;
use Nucleos\Form\Type\DACHCountryType;
use Nucleos\Form\Type\DateOutputType;
use Nucleos\Form\Type\GenderType;
use Nucleos\Form\Type\NumberOutputType;
use Nucleos\Form\Type\OutputType;
use Nucleos\Form\Type\TimePickerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(DACHCountryType::class)
            ->tag('form.type', [])

        ->set(GenderType::class)
            ->tag('form.type', [])

        ->set(OutputType::class)
            ->tag('form.type', [])

        ->set(DateOutputType::class)
            ->tag('form.type', [])

        ->set(NumberOutputType::class)
            ->tag('form.type', [])

        ->set(BatchTimeType::class)
            ->tag('form.type', [])

        ->set(TimePickerType::class)
            ->tag('form.type', [])

        ->set(ImageTypeExtension::class)
            ->tag('form.type_extension', [
                'extended-type' => FileType::class,
            ])

        ->set(DownloadTypeExtension::class)
            ->tag('form.type_extension', [
                'extended-type' => FileType::class,
            ])

    ;
};
