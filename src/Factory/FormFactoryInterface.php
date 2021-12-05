<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Factory;

use Symfony\Component\Form\FormInterface;

/**
 * @deprecated
 */
interface FormFactoryInterface
{
    /**
     * Creates a new form instance.
     *
     * @param mixed|null $data
     */
    public function create($data = null, array $options = []): FormInterface;
}
