<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Test;

use Core23\Form\Handler\FormHandlerInterface;
use Core23\Form\Test\AbstractFormHandlerTest;
use Core23\Form\Tests\Fixtures\AlwaysErrorFormHandler;
use Core23\Form\Tests\Fixtures\DemoFormHandler;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractFormHandlerTestCaseTest extends AbstractFormHandlerTest
{
    private $formHandler;

    public function testExecutePreProcess(): void
    {
        $this->formHandler = new DemoFormHandler();

        $object  = new stdClass();
        $request = new Request();

        $this->assertNull($this->executePreProcess($request, $object));
    }

    public function testExecutePreProcessWithError(): void
    {
        $this->formHandler = new AlwaysErrorFormHandler();

        $object  = new stdClass();
        $request = new Request();

        $this->assertError('my error', ['foo' => 'bar']);

        $this->assertNotNull($this->executePreProcess($request, $object));
    }

    public function testExecuteProcess(): void
    {
        $this->formHandler = new DemoFormHandler();

        $object  = new stdClass();
        $request = new Request();

        $this->assertTrue($this->executeProcess($request, $object));
    }

    public function testExecuteProcessWithError(): void
    {
        $this->formHandler = new AlwaysErrorFormHandler();

        $object  = new stdClass();
        $request = new Request();

        $this->assertError('my error', ['foo' => 'bar']);

        $this->assertFalse($this->executeProcess($request, $object));
    }

    public function testExecutePostProcess(): void
    {
        $this->formHandler = new DemoFormHandler();

        $object   = new stdClass();
        $request  = new Request();
        $response = new Response();

        $this->assertSame($response, $this->executePostProcess($request, $response, $object));
    }

    public function testExecutePostProcessWithError(): void
    {
        $this->formHandler = new AlwaysErrorFormHandler();

        $object   = new stdClass();
        $request  = new Request();
        $response = new Response();

        $this->assertError('my error', ['foo' => 'bar']);

        $this->assertNull($this->executePostProcess($request, $response, $object));
    }

    /**
     * /**
     * @return FormHandlerInterface
     */
    protected function createFormHandler(): FormHandlerInterface
    {
        return $this->formHandler;
    }
}
