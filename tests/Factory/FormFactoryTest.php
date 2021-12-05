<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Factory;

use Nucleos\Form\Factory\FormFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use stdClass;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactory;
use Symfony\Component\Form\FormInterface;

final class FormFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy<SymfonyFormFactory>
     */
    private $formFactory;

    protected function setUp(): void
    {
        $this->formFactory = $this->prophesize(SymfonyFormFactory::class);
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

        static::assertSame($form->reveal(), $factory->create($data));
    }
}
