<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensions\Test;

use Core23\FormExtensions\Form\Handler\FormHandlerInterface;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractFormHandlerTest extends TestCase
{
    protected $form;

    protected $request;

    protected $session;

    /**
     * @var array
     */
    private $errors;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->form = $this->createMock(FormInterface::class);

        $this->session = $this->createMock(Session::class);

        $this->request = $this->createMock(Request::class);
        $this->request->expects($this->any())->method('getSession')
            ->will($this->returnValue($this->session));

        $this->errors = [];
    }

    /**
     * @return FormHandlerInterface
     */
    abstract protected function createFormHandler(): FormHandlerInterface;

    /**
     * Executes the preProcess method.
     *
     * @param Request    $request
     * @param mixed|null $data
     *
     * @return Response|null
     */
    final protected function executePreProcess(Request $request, $data = null): ?Response
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->expects($this->once())->method('getData')
                ->will($this->returnValue($data));
        }

        $method = new \ReflectionMethod($handler, 'preProcess');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Executes the process method.
     *
     * @param Request    $request
     * @param mixed|null $data
     *
     * @return bool
     */
    final protected function executeProcess(Request $request, $data = null): bool
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->expects($this->any())->method('getData')
                ->will($this->returnValue($data));
        }

        $method = new \ReflectionMethod($handler, 'process');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Executes the postProcess method.
     *
     * @param Request    $request
     * @param Response   $response
     * @param mixed|null $data
     *
     * @return Response|null
     */
    final protected function executePostProcess(Request $request, Response $response, $data = null): ?Response
    {
        $handler = $this->createFormHandler();

        if (null !== $data) {
            $this->form->expects($this->once())->method('getData')
                ->will($this->returnValue($data));
        }

        $method = new \ReflectionMethod($handler, 'postProcess');
        $method->setAccessible(true);

        $this->checkCalledErrors();

        $result = $method->invoke($handler, $this->form, $request, $response);

        $this->checkUncalledErrors();

        return $result;
    }

    /**
     * Assets an error.
     *
     * @param string $message
     * @param array  $messageParameters
     */
    final protected function assertError(string $message, array $messageParameters = []): void
    {
        $this->errors[] = [
            'message'    => $message,
            'parameters' => $messageParameters,
            'count'      => 0,
        ];
    }

    /**
     * @throws callable
     *
     * @return Constraint
     */
    private function equalToErrors(): Constraint
    {
        return $this->callback(function ($error) {
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
        $count = count($this->errors);
        if (0 === $count) {
            $this->form->expects($this->never())->method('addError');
        } else {
            $this->form->expects($this->exactly($count))->method('addError')
                ->with($this->equalToErrors());
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
