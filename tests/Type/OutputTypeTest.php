<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\OutputType;

class OutputTypeTest extends BaseTypeTest
{
    public function testSubmitNull($expected = null, $norm = null, $view = ''): void
    {
        parent::testSubmitNull($expected, $norm, $view);
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'empty', $expectedData = null): void
    {
        $builder = $this->factory->createBuilder($this->getTestedType());

        $form = $builder->setEmptyData($emptyData)->getForm()->submit(null);

        static::assertSame('', $form->getViewData());
        static::assertSame($expectedData, $form->getNormData());
        static::assertSame($expectedData, $form->getData());
    }

    protected function getTestedType(): string
    {
        return OutputType::class;
    }
}
