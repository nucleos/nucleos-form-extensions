<?php

namespace Core23\Form\Tests\Fixtures;

use Core23\Form\Handler\AbstractFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class SimpleFormHandler extends AbstractFormHandler
{
    /**
     * @inheritDoc
     */
    protected function process(FormInterface $form, Request $request): bool
    {
        return true;
    }
}
