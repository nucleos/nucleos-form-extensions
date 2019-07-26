<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\BatchTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

final class BatchTimeTypeTest extends BaseTypeTest
{
    public function testGetParent(): void
    {
        $type = new BatchTimeType();

        static::assertSame(FormType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new BatchTimeType();

        static::assertSame('batch_time', $type->getBlockPrefix());
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        $form = $this->create(null, [
            'required' => false,
        ]);
        $form->submit(null);

        static::assertSame($expected, $form->getData());
        static::assertSame($norm, $form->getNormData());
        static::assertSame($view, $form->getViewData());
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = null, $expectedData = null): void
    {
        static::markTestSkipped('emptyData is not supported');
    }

    protected function getTestedType(): string
    {
        return BatchTimeType::class;
    }
}
