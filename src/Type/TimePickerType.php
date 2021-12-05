<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TimePickerType extends AbstractType
{
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $format    = 'HH';

        if (true === $options['with_minutes']) {
            $format .= ':mm';
            $options['dp_use_minutes'] = true;
        }

        if (true === $options['with_seconds']) {
            $format .= ':ss';
            $options['dp_use_seconds'] = true;
        }

        $view->vars['moment_format']         = $format;
        $view->vars['datepicker_use_button'] = (bool) $options['datepicker_use_button'];
        $view->vars['dp_options']            = self::createDpOptions($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'input'                 => 'datetime',
                'widget'                => 'single_text',
                'datepicker_use_button' => true,
                'dp_pick_date'          => false,
                'dp_minute_stepping'    => 1,
            ])
            ->setAllowedTypes('input', 'string')
            ->setAllowedTypes('widget', 'string')
        ;
    }

    public function getParent(): ?string
    {
        return TimeType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'nucleos_type_time_picker';
    }

    private static function createDpOptions(array $options): array
    {
        $dpOptions = [];

        foreach ($options as $key => $value) {
            if (false !== strpos($key, 'dp_')) {
                // We remove 'dp_' and camelize the options names
                $dpKey = substr($key, 3);
                $dpKey = preg_replace_callback(
                    '/_([a-z])/',
                    static function ($c) {
                        return strtoupper($c[1]);
                    },
                    $dpKey
                );

                $dpOptions[$dpKey] = $value;
            }
        }

        return $dpOptions;
    }
}
