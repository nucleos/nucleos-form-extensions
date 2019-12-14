<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Fixtures;

use Core23\Form\Handler\AbstractFormHandler;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AlwaysErrorFormHandler extends AbstractFormHandler
{
    protected function process(FormInterface $form, Request $request): bool
    {
        $form->addError(new FormError('my error', null, ['foo' => 'bar']));

        return false;
    }

    protected function preProcess(FormInterface $form, Request $request): ?Response
    {
        $form->addError(new FormError('my error', null, ['foo' => 'bar']));

        return new Response();
    }

    protected function postProcess(FormInterface $form, Request $request, Response $response): ?Response
    {
        $form->addError(new FormError('my error', null, ['foo' => 'bar']));

        return null;
    }
}
