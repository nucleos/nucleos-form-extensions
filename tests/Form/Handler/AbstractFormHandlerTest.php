<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Form\Handler;

use Nucleos\Form\Handler\Exception\InvalidCallbackException;
use Nucleos\Form\Tests\Fixtures\SimpleFormHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AbstractFormHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $request  = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $form = $this->createMock(FormInterface::class);
        $form->expects(static::once())->method('handleRequest')
            ->with(static::equalTo($request))
        ;
        $form->expects(static::once())->method('isValid')
            ->willReturn(true)
        ;
        $form->expects(static::once())->method('isSubmitted')
            ->willReturn(true)
        ;

        $handler = new SimpleFormHandler();

        $result = $handler->handle($form, $request, static function () use ($response) {
            return $response;
        });

        static::assertSame($response, $result);
    }

    public function testHandleInvalidCallback(): void
    {
        $this->expectException(InvalidCallbackException::class);

        $request = $this->createMock(Request::class);

        $form = $this->createMock(FormInterface::class);
        $form->expects(static::once())->method('handleRequest')
            ->with(static::equalTo($request))
        ;
        $form->expects(static::once())->method('isValid')
            ->willReturn(true)
        ;
        $form->expects(static::once())->method('isSubmitted')
            ->willReturn(true)
        ;

        $handler = new SimpleFormHandler();

        $handler->handle($form, $request, static function () {
            return null;
        });
    }
}
