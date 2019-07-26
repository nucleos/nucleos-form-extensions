<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Fixtures;

use Core23\Form\Handler\AbstractFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class DemoFormHandler extends AbstractFormHandler
{
    protected function process(FormInterface $form, Request $request): bool
    {
        return true;
    }
}
