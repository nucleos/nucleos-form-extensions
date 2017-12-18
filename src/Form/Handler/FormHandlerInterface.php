<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Handler;

use Core23\FormExtensionsBundle\Form\Handler\Exception\InvalidCallbackException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface FormHandlerInterface
{
    /**
     * Handels form processing.
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param callable      $callback
     *
     * @throws InvalidCallbackException
     *
     * @return Response|null
     */
    public function handle(FormInterface $form, Request $request, callable $callback): ?Response;
}
