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

        ->set('nucleos_form.type.dach_country', DACHCountryType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'nucleos_country',
            ])

        ->set('nucleos_form.type.gender', GenderType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'gender',
            ])

        ->set('nucleos_form.type.output', OutputType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'output',
            ])

        ->set('nucleos_form.type.date_output', DateOutputType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'date_output',
            ])

        ->set('nucleos_form.type.number_output', NumberOutputType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'number_output',
            ])

        ->set('nucleos_form.type.batch_time', BatchTimeType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'batch_time',
            ])

        ->alias('nucleos_form.time_picker', 'nucleos_form.type.time_picker')

        ->set('nucleos_form.type.time_picker', TimePickerType::class)
            ->tag('validator.constraint_validator', [
                'form.type' => 'nucleos_type_time_picker',
            ])

        ->set('nucleos_form.image_type_extension', ImageTypeExtension::class)
            ->tag('form.type_extension', [
                'alias'         => 'file',
                'extended-type' => FileType::class,
            ])

        ->set('nucleos_form.download_type_extension', DownloadTypeExtension::class)
            ->tag('form.type_extension', [
                'alias'         => 'file',
                'extended-type' => FileType::class,
            ])

    ;
};
