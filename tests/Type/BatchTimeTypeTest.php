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

class BatchTimeTypeTest extends BaseTypeTest
{
    public function testGetParent(): void
    {
        $type = new BatchTimeType();

        $this->assertSame(FormType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new BatchTimeType();

        $this->assertSame('batch_time', $type->getBlockPrefix());
    }

    /**
     * {@inheritdoc}
     */
    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        $form = $this->create(null, [
            'required' => false,
        ]);
        $form->submit(null);

        $this->assertSame($expected, $form->getData());
        $this->assertSame($norm, $form->getNormData());
        $this->assertSame($view, $form->getViewData());
    }

    /**
     * {@inheritdoc}
     */
    public function testSubmitNullUsesDefaultEmptyData($emptyData = null, $expectedData = null): void
    {
        $this->markTestSkipped('emptyData is not supported');
    }

    protected function getTestedType(): string
    {
        return BatchTimeType::class;
    }
}
