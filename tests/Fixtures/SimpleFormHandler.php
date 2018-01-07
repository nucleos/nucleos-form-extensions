<?php

namespace Core23\FormExtensionsBundle\Tests\Fixtures;

use Core23\FormExtensionsBundle\Form\Handler\AbstractFormHandler;
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
