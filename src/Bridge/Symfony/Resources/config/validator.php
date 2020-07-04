<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\Form\Validator\Constraints\BatchTimeAfterValidator;
use Nucleos\Form\Validator\Constraints\DateAfterValidator;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('nucleos_form.validator.date_after', DateAfterValidator::class)
            ->public()
            ->tag('validator.constraint_validator', [
                'alias' => 'nucleos_form.validator.date_after',
            ])

        ->set('nucleos_form.validator.batch_time_after', BatchTimeAfterValidator::class)
            ->public()
            ->tag('validator.constraint_validator', [
                'alias' => 'nucleos_form.validator.batch_time_after',
            ])

    ;
};
