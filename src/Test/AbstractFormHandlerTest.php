<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Test;

use Nucleos\Form\Handler\FormHandlerInterface;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractFormHandlerTest extends TestCase
{
    /**
     * @var FormInterface&MockObject
     */
    protected FormInterface $form;

    protected Request $request;

    /**
     * @var MockObject&Session
     */
    protected Session $session;

    private array $errors;

    protected function setUp(): void
    {
        $this->form = $this->createMock(FormInterface::class);

        $this->session = $this->createMock(Session::class);

        $this->request = new Request();
        $this->request->setSession($this->session);

        $this->errors = [];
    }

    abstract protected function createFormHandler(): FormHandlerInterface;

    /**
     * Executes the preProcess method.
     *
     * @throws ReflectionException
     */
    final protected function executePreProcess(Request $request, mixed $data = null): ?Response
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->method('getData')
                ->willReturn($data)
            ;
        }

        $method = new ReflectionMethod($handler, 'preProcess');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Executes the process method.
     *
     * @throws ReflectionException
     */
    final protected function executeProcess(Request $request, mixed $data = null): bool
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->method('getData')
                ->willReturn($data)
            ;
        }

        $method = new ReflectionMethod($handler, 'process');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Executes the postProcess method.
     *
     * @throws ReflectionException
     */
    final protected function executePostProcess(Request $request, Response $response, mixed $data = null): ?Response
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->method('getData')
                ->willReturn($data)
            ;
        }

        $method = new ReflectionMethod($handler, 'postProcess');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request, $response);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Assets an error.
     */
    final protected function assertError(string $message, array $messageParameters = []): void
    {
        $this->errors[] = [
            'message'    => $message,
            'parameters' => $messageParameters,
            'count'      => 0,
        ];
    }

    private function equalToErrors(): Constraint
    {
        return static::callback(function ($error) {
            if ($error instanceof FormError) {
                foreach ($this->errors as &$data) {
                    if ($error->getMessage() === $data['message'] && $error->getMessageParameters() === $data['parameters']) {
                        ++$data['count'];

                        return true;
                    }
                }

                throw new AssertionFailedError(
                    sprintf("Method 'addError' was not expected to be called with message '%s'", $error->getMessage())
                );
            }

            return false;
        });
    }

    private function checkCalledErrors(): void
    {
        $count = \count($this->errors);
        if (0 === $count) {
            $this->form->expects(static::never())->method('addError');
        } else {
            $this->form->expects(static::exactly($count))->method('addError')
                ->with($this->equalToErrors())
            ;
        }
    }

    /**
     * @throws AssertionFailedError
     */
    private function checkUncalledErrors(): void
    {
        foreach ($this->errors as $data) {
            if (0 === $data['count']) {
                throw new AssertionFailedError(
                    sprintf("Method 'addError' was expected to be called with message '%s' actually was not called", $data['message'])
                );
            }
        }
    }
}
