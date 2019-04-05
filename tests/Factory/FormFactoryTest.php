<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Factory;

use Core23\Form\Factory\FormFactory;
use Core23\Form\Factory\FormFactoryInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactory;
use Symfony\Component\Form\FormInterface;

class FormFactoryTest extends TestCase
{
    private $formFactory;

    protected function setUp()
    {
        $this->formFactory = $this->prophesize(SymfonyFormFactory::class);
    }

    public function testItIsInstantiable(): void
    {
        $factory = new FormFactory($this->formFactory->reveal(), 'foo', 'bar');

        $this->assertInstanceOf(FormFactoryInterface::class, $factory);
    }

    public function testCreate(): void
    {
        $data = new stdClass();

        $form = $this->prophesize(FormInterface::class);

        $this->formFactory->createNamed('foo', 'bar', $data, [
            'method'            => 'POST',
            'validation_groups' => ['MyGroup'],
        ])
            ->willReturn($form)
        ;

        $factory = new FormFactory($this->formFactory->reveal(), 'foo', 'bar', ['MyGroup']);

        $this->assertSame($form->reveal(), $factory->create($data));
    }
}
