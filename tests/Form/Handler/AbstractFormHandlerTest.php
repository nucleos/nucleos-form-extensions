<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Tests\Form\Handler;

use Core23\FormExtensionsBundle\Form\Handler\AbstractFormHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractFormHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Request $request */
        $request = $this->createMock(Request::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Response $response */
        $response = $this->createMock(Response::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $form */
        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')
            ->with($this->equalTo($request));
        $form->expects($this->once())->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())->method('isSubmitted')
            ->will($this->returnValue(true));

        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractFormHandler $handler */
        $handler = $this->createMock(AbstractFormHandler::class);
        $handler->expects($this->once())->method('process')
            ->with($this->equalTo($form), $this->equalTo($request))
            ->will($this->returnValue(true));

        $result = $handler->handle($form, $request, function () use ($response) {
            return $response;
        });

        $this->assertSame($response, $result);
    }

    /**
     * @expectedException \Core23\FormExtensionsBundle\Form\Handler\Exception\InvalidCallbackException
     */
    public function testHandleInvalidCallback(): void
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Request $request */
        $request = $this->createMock(Request::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $form */
        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')
            ->with($this->equalTo($request));
        $form->expects($this->once())->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())->method('isSubmitted')
            ->will($this->returnValue(true));

        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractFormHandler $handler */
        $handler = $this->createMock(AbstractFormHandler::class);
        $handler->expects($this->once())->method('process')
            ->with($this->equalTo($form), $this->equalTo($request))
            ->will($this->returnValue(true));

        $handler->handle($form, $request, function () {
            return null;
        });
    }
}
